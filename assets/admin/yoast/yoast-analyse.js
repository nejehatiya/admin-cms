import babelPolyfill from 'babel-polyfill';
import { Paper, ContentAssessor, Researcher, SnippetPreview,App } from "yoastseo";
import SEOAssessor from 'yoastseo/src/seoAssessor';
import ReadabilityScoreAggregator from 'yoastseo/src/parsedPaper/assess/scoreAggregators/ReadabilityScoreAggregator';
import SEOScoreAggregator from 'yoastseo/src/parsedPaper/assess/scoreAggregators/SEOScoreAggregator';

import Jed from 'jed';
import snarkdown from 'snarkdown';
import {zipObject,omit} from 'lodash'
import Presenter from './Presenter.js';
let limit_title = 600;
let limit_desc = 156;
let ranges = {'na': {'start':0,'end':0},'mauvais': {'start':1,'end':40},'moyenne': {'start':41,'end':70},'bien': {'start':71,'end':100}};
let get_html = null;
let html_filter = "";
let function_global = require('../popup-modeles/js/function-global');
import {encode,decode} from 'html-entities';
export function yoastScores(text,keyword,title,description,url,metaDescription,permalink){
    /*let title_width = $("<span class='snippet-title'>À quel prix s'attendre pour la dépannage d'un rideau métallique ?</span>").insertAfter($('#seo-content-title'));
    title_width = $('.snippet-title').width();
    alert(title_width);
    let meta_height  = 900;*/
    const paper = new Paper(text, {
        keyword: keyword,
        title: title,
        description: description,
        url: url,
        synonyms: '',
        titleWidth: $('.seo-title-width').width(),
        locale: 'fr_FR',
        permalink: permalink,
    });
    console.log('paper',paper)
    const contentAssessor = new ContentAssessor(i18n());
    console.log('contentAssessor',contentAssessor);
    contentAssessor.assess(paper);

    const seoAssessor = new SEOAssessor(i18n());
    seoAssessor.assess(paper);
    
    const final_scores = getScores(seoAssessor,contentAssessor);
    //this.seo = final_scores.seo;
    //this.content = final_scores.content;
    
    const score_readability = new ReadabilityScoreAggregator();
    score_readability.setLocale('fr_FR');
    let total_readability = score_readability.aggregate(final_scores.content);
    const score_seo = new SEOScoreAggregator();
    //score_seo.setLocale('fr');
    let total_seo = score_seo.aggregate(final_scores.seo);
    console.log('total_seo',total_seo);
    genrateHtmlContent(final_scores);
    $("#wpseo-seo-score-icon").html("("+(parseFloat(total_seo)>0?total_seo:0)+")");
    $("#wpseo-readability-score-icon").html("("+(parseFloat(total_readability)>0?total_readability:0)+")");
    $('#score-seo-total').val(parseFloat(total_seo)>0?total_seo:0);
    $('#score-readability-total').val(parseFloat(total_readability)>0?total_readability:0);

    $("span.note-seo").html(noteScoreReadability(total_seo)).parent().attr('class','infos-yoast yoast-'+noteScoreReadability(total_seo));
    $("span.note-readability").html(noteScoreReadability(total_readability)).parent().attr('class','infos-yoast yoast-'+noteScoreReadability(total_readability));
    return final_scores;
}
/**** function to start genrate html */
export function genrateHtmlContent(resultat){
    console.log('resultat',resultat)
    let seo = resultat.seo;
    let content = resultat.content;
    
    console.log('content',content)
    let seo_html="";
    let content_html="";

    let content_rate = {'bad':[],'ok':[],'good':[]};
    let seo_rate = {'bad':[],'ok':[],'good':[]};
    let i = 0;
    for(let item in content){
        console.log('item',content[item]['text'])
        content_rate[content[item]['rating']].push("<li class='content "+content[item]['rating']+"'>"+content[item]['text']+"</li>");
    }
    if(content_rate['bad'].length){
        content_html += '<li class="title"><span>Problèmes ('+content_rate['bad'].length+')</span><ul>'+content_rate['bad'].join('')+'</ul></li>'
    }
    if(content_rate['ok'].length){
        content_html += '<li class="title"><span>Améliorations ('+content_rate['ok'].length+')</span><ul>'+content_rate['ok'].join('')+'</ul></li>'
    }
    if(content_rate['good'].length){
        content_html += '<li class="title"><span>Déjà optimisé ('+content_rate['good'].length+')</span><ul>'+content_rate['good'].join('')+'</ul></li>'
    }
    for(let item2 in seo){
        seo_rate[seo[item2]['rating']].push("<li class='seo "+seo[item2]['rating']+"'>"+seo[item2]['text']+"</li>");
    }
    if(seo_rate['bad'].length){
        seo_html += '<li class="title"><span>Problèmes ('+seo_rate['bad'].length+')</span><ul>'+seo_rate['bad'].join('')+'</ul></li>'
    }
    if(seo_rate['ok'].length){
        seo_html += '<li class="title"><span>Améliorations ('+seo_rate['ok'].length+')</span><ul>'+seo_rate['ok'].join('')+'</ul></li>'
    }
    if(seo_rate['good'].length){
        seo_html += '<li class="title"><span>Déjà optimisé ('+seo_rate['good'].length+')</span><ul>'+seo_rate['good'].join('')+'</ul></li>'
    }
    $('.seo-content').html(content_html);
    $('.seo-html').html(seo_html );
}
export function getScores(seoAssessor, contentAssessor) {
    return {
      seo: new Presenter().getScoresWithRatings(seoAssessor),
      content: new Presenter().getScoresWithRatings(contentAssessor)
    }
}
export function getScoresAsHTML(scores) {
    return new Presenter().getScoresAsHTML(h, scores);
}
export function i18n() {
    return new Jed({
        'domain': `js-text-analysis`,
        'locale_data': {
            "js-text-analysis": { 
                "": {
                    "domain"        : "js-text-analysis",
                    "lang"          : "fr",
                    "plural_forms" : "nplurals=2; plural=(n != 1);"
                },
                "%1$sKeyphrase in title%3$s: Not all the words from your keyphrase \"%4$s\" appear in the SEO title. %2$sFor the best SEO results write the exact match of your keyphrase in the SEO title, and put the keyphrase at the beginning of the title%3$s.":["%1$sRequête dans le titre%3$s : Tous les mots de votre requête « %4$s » n’apparaissent pas dans le méta titre. %2$sPour obtenir de meilleurs résultats, écrivez votre requête dans le méta titre et mettez-la au début de la phrase de préférence%3$s."],"%1$sKeyphrase in title%3$s: Does not contain the exact match. %2$sTry to write the exact match of your keyphrase in the SEO title and put it at the beginning of the title%3$s.":["%1$sRequête dans le titre%3$s : Elle n’est pas présente. %2$sEssayez de l’écrire exactement dans le méta titre et au début de la phrase, de préférence%3$s."],"%1$sKeyphrase in title%3$s: The exact match of the focus keyphrase appears in the SEO title, but not at the beginning. %2$sMove it to the beginning for the best results%3$s.":["%1$sRequête dans le titre%3$s : Les mots de votre requête apparaissent bien dans le méta titre, mais pas au début. %2$sDéplacez-les en début de phrase pour de meilleurs résultats%3$s."],"Google preview":["Aperçu Google"],"Has feedback":["Dispose d’un retour"],"Content optimization:":["Optimisation du contenu :"],"%1$sFlesch Reading Ease%2$s: The copy scores %3$s in the test, which is considered %4$s to read. %5$s":["%1$sTest de lisibilité Flesch%2$s : Votre contenu obtient %3$s au test, ce qui est considéré comme %4$s à lire. %5$s"],"%3$sImage alt attributes%5$s: Out of %2$d images on this page, %1$d have alt attributes with words from your keyphrase or synonyms. That's a bit much. %4$sOnly include the keyphrase or its synonyms when it really fits the image%5$s.":["%3$sTextes alternatifs%5$s : Sur %2$d images de cette publication, %1$d ont des textes alternatifs qui contiennent votre requête cible ou ses synonymes. C’est un peu trop. %4$sN’utilisez ces termes que lorsqu’ils correspondent réellement à l’image%5$s."],"%1$sImage alt attributes%2$s: Good job!":["%1$sTextes alternatifs%2$s : Bon travail !"],"%3$sImage alt attributes%5$s: Out of %2$d images on this page, only %1$d has an alt attribute that reflects the topic of your text. %4$sAdd your keyphrase or synonyms to the alt tags of more relevant images%5$s!":["%3$sTextes alternatifs%5$s : Sur %2$d images de cette publication, seulement %1$d a un texte alternatif qui reflète le sujet de votre texte. %4$sAjoutez votre requête cible ou ses synonymes aux textes alternatifs pertinents%5$s !","%3$sTextes alternatifs%5$s : Sur %2$d images de cette publication, seulement %1$d ont un texte alternatif qui reflète le sujet de votre texte. %4$sAjoutez votre requête cible ou ses synonymes aux textes alternatifs pertinents%5$s !"],"%1$sImage alt attributes%3$s: Images on this page do not have alt attributes that reflect the topic of your text. %2$sAdd your keyphrase or synonyms to the alt tags of relevant images%3$s!":["%1$sTextes alternatifs%3$s : Les images de cette publication n’ont pas de texte alternatif qui reflètent le sujet de votre texte. %2$sAjoutez votre requête cible ou ses synonymes aux textes alternatifs qui seraient pertinents%3$s !"],"%1$sImage alt attributes%3$s: Images on this page have alt attributes, but you have not set your keyphrase. %2$sFix that%3$s!":["%1$sTextes alternatifs%3$s : Les images de cette publication ont des textes alternatifs mais vous n’avez pas défini de requête cible. %2$sRenseignez-en une%3$s !"],"%1$sKeyphrase in subheading%2$s: %3$s of your H2 and H3 subheadings reflects the topic of your copy. Good job!":["%1$sPhrase clé dans le sous-titre%2$s : %3$s de vos sous-titres H2 et H3 reflètent le sujet de votre texte. Bon travail !","%1$sPhrase clé dans le sous-titre%2$s : %3$s de vos sous-titres H2 et H3 reflètent le sujet de votre texte. Bon travail !"],"%1$sKeyphrase in subheading%2$s: Your H2 or H3 subheading reflects the topic of your copy. Good job!":["%1$sPhrase clé dans le sous-titre%2$s : votre sous-titre H2 ou H3 reflète le sujet de votre texte. Bon travail !","%1$sPhrase clé dans le sous-titre%2$s : votre sous-titre H2 ou H3 reflète le sujet de votre texte. Bon travail !"],"%1$sKeyphrase in subheading%3$s: %2$sUse more keyphrases or synonyms in your H2 and H3 subheadings%3$s!":["%1$sPhrase clé dans le sous-titre%3$s : %2$sUtilisez plus de phrases clés ou de synonymes dans vos sous-titres H2 et H3%3$s !","%1$sPhrase clé dans le sous-titre%3$s : %2$sUtilisez plus de phrases clés ou de synonymes dans vos sous-titres H2 et H3%3$s !"],"%1$sSingle title%3$s: H1s should only be used as your main title. Find all H1s in your text that aren't your main title and %2$schange them to a lower heading level%3$s!":["%1$sTitre de niveau 1%3$s : Les H1 ne devraient être utilisés que pour votre titre principal. Trouvez-les dans votre texte et %2$spassez-les à un niveau inférieur%3$s !"],"%1$sKeyphrase density%2$s: The focus keyphrase was found 0 times. That's less than the recommended minimum of %3$d times for a text of this length. %4$sFocus on your keyphrase%2$s!":["%1$sDensité de requête%2$s : La requête cible a été trouvée 0 fois. C’est moins que le minimum requis de %3$d fois pour un texte de cette taille. %4$sConcentrez-vous sur votre requête%2$s !"],"%1$sKeyphrase density%2$s: The focus keyphrase was found %5$d time. That's less than the recommended minimum of %3$d times for a text of this length. %4$sFocus on your keyphrase%2$s!":["%1$sDensité de requête%2$s : La requête cible a été trouvé %5$d fois. C’est moins que le minimum recommandé de %3$d fois pour un texte de cette taille. %4$sConcentrez-vous sur votre requête%2$s !","%1$sDensité de requête%2$s : La requête cible a été trouvé %5$d fois. C’est moins que le minimum recommandé de %3$d fois pour un texte de cette taille. %4$sConcentrez-vous sur votre requête%2$s !"],"%1$sKeyphrase density%2$s: The focus keyphrase was found %3$d time. This is great!":["%1$sDensité de requête%2$s : La requête cible a été trouvée %3$d fois. C’est très bien !","%1$sDensité de requête%2$s : La requête cible a été trouvée %3$d fois. C’est très bien !"],"%1$sKeyphrase density%2$s: The focus keyphrase was found %5$d time. That's more than the recommended maximum of %3$d times for a text of this length. %4$sDon't overoptimize%2$s!":["%1$sDensité de requête%2$s : La requête cible a été trouvé %5$d fois. C’est plus que le maximum recommandé de %3$d fois pour un texte de cette taille. %4$sNe sur-optimisez pas%2$s !","%1$sDensité de requête%2$s : La requête cible a été trouvé %5$d fois. C’est plus que le maximum recommandé de %3$d fois pour un texte de cette taille. %4$sNe sur-optimisez pas%2$s !"],"%1$sKeyphrase density%2$s: The focus keyphrase was found %5$d time. That's way more than the recommended maximum of %3$d times for a text of this length. %4$sDon't overoptimize%2$s!":["%1$sDensité de requête%2$s : La requête cible a été trouvé %5$d fois. C’est bien plus que le maximum recommandé de %3$d fois pour un texte de cette taille. %4$sNe sur-optimisez pas%2$s !","%1$sDensité de requête%2$s : La requête cible a été trouvé %5$d fois. C’est bien plus que le maximum recommandé de %3$d fois pour un texte de cette taille. %4$sNe sur-optimisez pas%2$s !"],"%1$sFunction words in keyphrase%3$s: Your keyphrase \"%4$s\" contains function words only. %2$sLearn more about what makes a good keyphrase.%3$s":["%1$sMots-outils dans la requête%3$s : Votre requête « %4$s » contient uniquement des mots-outils. %2$sApprenez à concevoir de bonnes requêtes.%3$s"],"%1$sKeyphrase length%3$s: %2$sSet a keyphrase in order to calculate your SEO score%3$s.":["%1$sLongueur de la requête%3$s : %2$sSaisissez une requête pour calculer votre score SEO%3$s."],"%1$sKeyphrase in slug%2$s: More than half of your keyphrase appears in the slug. That's great!":["%1$sRequête dans le slug%2$s : Plus de la moitié de votre requête apparaît dans le slug. C’est super !"],"%1$sKeyphrase in slug%3$s: (Part of) your keyphrase does not appear in the slug. %2$sChange that%3$s!":["%1$sRequête dans le slug%3$s : (Une partie de) votre requête n’apparaît pas dans le slug. %2$sAjoutez-en plus%3$s !"],"%1$sKeyphrase in slug%2$s: Great work!":["%1$sRequête dans le slug%2$s : Bon travail !"],"%1$sKeyphrase in title%2$s: The exact match of the focus keyphrase appears at the beginning of the SEO title. Good job!":["%1$sRequête dans le titre%2$s : Les mots de votre requête apparaissent au début de votre méta titre. Bon travail !"],"%1$sKeyphrase distribution%2$s: Good job!":["%1$sDistribution de la requête%2$s : Bon travail !"],"%1$sKeyphrase distribution%3$s: Uneven. Some parts of your text do not contain the keyphrase or its synonyms. %2$sDistribute them more evenly%3$s.":["%1$sDistribution de la requête%3$s : inégale. Certaines parties de votre texte ne contiennent ni la requête, ni ses synonymes. %2$sDistribuez-les plus équitablement%3$s."],"%1$sKeyphrase distribution%3$s: Very uneven. Large parts of your text do not contain the keyphrase or its synonyms. %2$sDistribute them more evenly%3$s.":["%1$sDistribution de la requête%3$s : très inégale. De grandes parties de votre texte ne contiennent ni votre requête, ni ses synonymes. %2$sDistribuez-les plus équitablement%3$s."],"%1$sKeyphrase distribution%3$s: %2$sInclude your keyphrase or its synonyms in the text so that we can check keyphrase distribution%3$s.":["%1$sDistribution de requête%3$s : %2$sÉcrivez votre requête ou ses synonymes dans le texte afin que nous puissions calculer leur distribution%3$s."],"%4$sPreviously used keyphrase%6$s: You've used this keyphrase %1$s%2$d times before%3$s. %5$sDo not use your keyphrase more than once%6$s.":["%4$sRequête déjà utilisée%6$s : Vous avez déjà utilisé cette requête %1$s%2$d fois%3$s. %5$sN’utilisez votre requête qu’une seule fois%6$s."],"%3$sPreviously used keyphrase%5$s: You've used this keyphrase %1$sonce before%2$s. %4$sDo not use your keyphrase more than once%5$s.":["%3$sRequête déjà utilisée%5$s : Vous avez déjà utilisé cette requête %1$sune fois%2$s. %4$sNe l’utilisez pas plus d’une fois%5$s."],"%1$sPreviously used keyphrase%2$s: You've not used this keyphrase before, very good.":["%1$sRequête déjà utilisée%2$s : Vous n’avez jamais utilisé cette requête, très bien."],"%1$sSlug stopwords%3$s: The slug for this page contains a stop word. %2$sRemove it%3$s!":["%1$sMots d’arrêt dans le slug%3$s : Le slug de cette page contient un mot d’arrêt. %2$sRetirez-le%3$s !","%1$sMots d’arrêt dans le slug%3$s : Le slug de cette page contient des mots d’arrêt. %2$sRetirez-les%3$s !"],"%1$sSlug too long%3$s: the slug for this page is a bit long. %2$sShorten it%3$s!":["%1$sSlug trop long%3$s : Le slug de cette page est un peu trop long. %2$sRaccourcissez-le%3$s !"],"%1$sImage alt attributes%3$s: No images appear on this page. %2$sAdd some%3$s!":["%1$sTexte alternatif des images%3$s : Il n’y a pas d’image dans cette page. %2$sAjoutez-en%3$s !"],"%1$sLink keyphrase%3$s: You're linking to another page with the words you want this page to rank for. %2$sDon't do that%3$s!":["%1$sAncre avec la requête cible%3$s : Vous faites un lien vers une autre page avec les mots de votre requête. %2$sNe faites pas ça%3$s !"],"This is far below the recommended minimum of %5$d word. %3$sAdd more content%4$s.":["C’est très en dessous du minimum recommandé de %5$d mot. %3$sAjoutez plus de contenu%4$s.","C’est très en dessous du minimum recommandé de %5$d mots. %3$sAjoutez plus de contenu%4$s."],"This is below the recommended minimum of %5$d word. %3$sAdd more content%4$s.":["C’est en dessous du minimum recommandé de %5$d mot. %3$sAjoutez plus de contenu%4$s.","C’est en dessous du minimum recommandé de %5$d mots. %3$sAjoutez plus de contenu%4$s."],"%2$sText length%4$s: The text contains %1$d word.":["%2$sLongueur du contenu%4$s : Le contenu a une longueur de %1$d mot.","%2$sLongueur du contenu%4$s : Le contenu a une longueur de %1$d mots."],"%2$sText length%3$s: The text contains %1$d word. Good job!":["%2$sLongueur du contenu%3$s : Le contenu a une longueur de %1$d mot. Bon travail !","%2$sLongueur du contenu%3$s : Le contenu a une longueur de %1$d mots. Bon travail !"],"%1$sKeyphrase in subheading%3$s: More than 75%% of your H2 and H3 subheadings reflect the topic of your copy. That's too much. %2$sDon't over-optimize%3$s!":[],"%1$sSEO title width%3$s: %2$sPlease create an SEO title%3$s.":["%1$sLongueur de méta titre%3$s : %2$sVeuillez créer un méta titre%3$s."],"%1$sSEO title width%3$s: The SEO title is wider than the viewable limit. %2$sTry to make it shorter%3$s.":["%1$sLargeur de méta titre%3$s : Le méta titre est plus large que la limite visible. %2$sEssayez de le raccourcir%3$s."],"%1$sSEO title width%2$s: Good job!":["%1$sLongueur de méta titre%2$s : Bon travail !"],"%1$sSEO title width%3$s: The SEO title is too short. %2$sUse the space to add keyphrase variations or create compelling call-to-action copy%3$s.":["%1$sLongueur de méta titre%3$s : Le méta titre est trop court. %2$sUtilisez l’espace disponible pour ajouter des variantes ou des appels à l’action%3$s."],"%1$sOutbound links%2$s: There are both nofollowed and normal outbound links on this page. Good job!":["%1$sLiens externes%2$s : Il y a des liens nofollow et des liens normaux dans cette page. Bon travail !"],"%1$sOutbound links%2$s: Good job!":["%1$sLiens externes%2$s : Bon travail !"],"%1$sOutbound links%3$s: All outbound links on this page are nofollowed. %2$sAdd some normal links%3$s.":["%1$sLiens externes%3$s : Tous les liens externes de cette page sont en nofollow. %2$sAjoutez des liens normaux%3$s."],"%1$sOutbound links%3$s: No outbound links appear in this page. %2$sAdd some%3$s!":["%1$sLiens externes%3$s : Il n’y a pas de lien externe dans cette page. %2$sAjoutez-en%3$s !"],"%1$sMeta description length%2$s: Well done!":["%1$sLongueur de méta description%2$s : Parfait !"],"%1$sMeta description length%3$s: The meta description is over %4$d characters. To ensure the entire description will be visible, %2$syou should reduce the length%3$s!":["%1$sLongueur de méta description%3$s : La méta description fait plus de %4$d caractères. Pour vous assurer qu’elle soit entièrement visible, %2$svous devriez la raccourcir%3$s !"],"%1$sMeta description length%3$s: The meta description is too short (under %4$d characters). Up to %5$d characters are available. %2$sUse the space%3$s!":["%1$sLongueur de méta description%3$s : La méta description est trop courte (en dessous de %4$d caractères). Vous pouvez aller jusqu’à %5$d caractères, %2$sutilisez cet espace%3$s !"],"%1$sMeta description length%3$s:  No meta description has been specified. Search engines will display copy from the page instead. %2$sMake sure to write one%3$s!":["%1$sLongueur de méta description%3$s : Aucune méta description n’a été renseignée. Les moteurs de recherches afficheront du contenu de la page à la place. %2$sAssurez-vous d’en écrire une%3$s !"],"%1$sKeyphrase in meta description%2$s: The meta description has been specified, but it does not contain the keyphrase. %3$sFix that%4$s!":["%1$sRequête dans la méta description%2$s : La méta description a été renseignée mais elle ne contient pas la requête. %3$sAjoutez-la%4$s !"],"%1$sKeyphrase in meta description%2$s: The meta description contains the keyphrase %3$s times, which is over the advised maximum of 2 times. %4$sLimit that%5$s!":["%1$sRequête dans la méta description%2$s : La méta description contient la requête %3$s fois, ce qui est supérieur au maximum conseillé de 2 fois. %4$sRéfrénez-vous%5$s !"],"%1$sKeyphrase in meta description%2$s: Keyphrase or synonym appear in the meta description. Well done!":["%1$sRequête dans la méta description%2$s : La requête ou son synonyme apparaît dans la méta description. Parfait !"],"%3$sKeyphrase length%5$s: The keyphrase is %1$d words long. That's way more than the recommended maximum of %2$d words. %4$sMake it shorter%5$s!":["%3$sLongueur de la requête%5$s : La requête fait %1$d mots. C’est bien plus que le maximum recommandé de %2$d mots. %4$sChoisissez-en une plus courte%5$s !"],"%3$sKeyphrase length%5$s: The keyphrase is %1$d words long. That's more than the recommended maximum of %2$d words. %4$sMake it shorter%5$s!":["%3$sLongueur de la requête%5$s : La requête fait %1$d mots. C’est au delà du maximum recommandé de %2$d mots. %4$sChoisissez-en une plus courte%5$s !"],"%1$sKeyphrase length%2$s: Good job!":["%1$sLongueur de la requête%2$s : Bon travail !"],"%1$sKeyphrase length%3$s: No focus keyphrase was set for this page. %2$sSet a keyphrase in order to calculate your SEO score%3$s.":["%1$sLongueur de la requête%3$s : Aucune requête cible n’a été définie pour cette page. %2$sRenseignez une requête afin de calculer votre score SEO%3$s."],"%1$sKeyphrase in introduction%3$s: Your keyphrase or its synonyms do not appear in the first paragraph. %2$sMake sure the topic is clear immediately%3$s.":["%1$sRequête dans l’introduction%3$s : Votre requête ou ses synonymes n’apparaissent pas dans le premier paragraphe. %2$sAssurez-vous que le sujet soit rapidement évoqué%3$s."],"%1$sKeyphrase in introduction%3$s:Your keyphrase or its synonyms appear in the first paragraph of the copy, but not within one sentence. %2$sFix that%3$s!":["%1$sRequête dans l’introduction%3$s : Votre requête ou ses synonymes apparaissent dans le premier paragraphe de la publication mais pas dans la même phrase. %2$sCorrigez ce point%3$s !"],"%1$sKeyphrase in introduction%2$s: Well done!":["%1$sRequête dans l’introduction%2$s : Parfait !"],"%1$sInternal links%2$s: There are both nofollowed and normal internal links on this page. Good job!":["%1$sMaillage interne%2$s : Il y a aussi bien des liens internes en nofollow que des liens normaux sur cette page. Bien joué !"],"%1$sInternal links%2$s: You have enough internal links. Good job!":["%1$sMaillage interne%2$s : Vous avez assez de liens internes. Bon travail !"],"%1$sInternal links%3$s: The internal links in this page are all nofollowed. %2$sAdd some good internal links%3$s.":["%1$sMaillage interne%3$s : Les liens internes dans cette page sont tous en nofollow. %2$sAjoutez donc de bons liens internes%3$s."],"%1$sInternal links%3$s: No internal links appear in this page, %2$smake sure to add some%3$s!":["%1$sMaillage interne%3$s : Il n’y a aucun lien interne dans cette page, %2$sassurez-vous d’en ajouter%3$s !"],"%1$sTransition words%2$s: Well done!":["%1$sMots de transition%2$s : Parfait !"],"%1$sTransition words%2$s: Only %3$s of the sentences contain transition words, which is not enough. %4$sUse more of them%2$s.":["%1$sMots de transition%2$s : Seulement %3$s des phrases contiennent des mots de transition, ce qui n’est pas suffisant. %4$sUtilisez-en plus souvent%2$s."],"%1$sTransition words%2$s: None of the sentences contain transition words. %3$sUse some%2$s.":["%1$sMots de transition%2$s : Aucune de vos phrases ne contient de mots de transition. %3$sUtilisez-en%2$s."],"%1$sNot enough content%2$s: %3$sPlease add some content to enable a good analysis%2$s.":["%1$sPas assez de contenu%2$s : %3$sVeuillez ajouter du contenu pour permettre une bonne analyse%2$s."],"%1$sSubheading distribution%2$s: You are not using any subheadings, but your text is short enough and probably doesn't need them.":["%1$sHiérarchie des titres%2$s : Vous n’utilisez pas de titres mais votre contenu est suffisamment court pour ne pas en mériter."],"%1$sSubheading distribution%2$s: You are not using any subheadings, although your text is rather long. %3$sTry and add some subheadings%2$s.":["%1$sHiérarchie des titres%2$s : Vous n’utilisez pas de titres alors que votre contenu est relativement long. %3$sEssayez d’en ajouter%2$s."],"%1$sSubheading distribution%2$s: %3$d section of your text is longer than %4$d words and is not separated by any subheadings. %5$sAdd subheadings to improve readability%2$s.":["%1$sHiérarchie des titres%2$s : %3$d section de votre contenu fait plus de %4$d mots et n’est pas séparée par des titres. %5$sAjoutez-en pour améliorer la lisibilité%2$s.","%1$sHiérarchie des titres%2$s : %3$d sections de votre contenu font plus de %4$d mots et ne sont pas séparées par des titres. %5$sAjoutez-en pour améliorer la lisibilité%2$s."],"%1$sSubheading distribution%2$s: Great job!":["%1$sHiérarchie des titres%2$s : Bon travail !"],"%1$sSentence length%2$s: %3$s of the sentences contain more than %4$s words, which is more than the recommended maximum of %5$s. %6$sTry to shorten the sentences%2$s.":["%1$sLongueur de phrase%2$s : %3$s des phrases contiennent plus de %4$s mots, ce qui est au delà du ratio maximum recommandé de %5$s. %6$sEssayez de raccourcir vos phrases%2$s."],"%1$sSentence length%2$s: Great!":["%1$sLongueur de phrase%2$s : Très bien !"],"%1$sConsecutive sentences%2$s: There is enough variety in your sentences. That's great!":["%1$sPhrases consécutives%2$s : Il y a suffisamment de variété dans vos phrases. C’est super !"],"%1$sConsecutive sentences%2$s: The text contains %3$d consecutive sentences starting with the same word. %5$sTry to mix things up%2$s!":["%1$sPhrases consécutives%2$s : Le texte contient %3$d phrases consécutives qui commencent avec le même mot. %5$sAjoutez un peu de variété%2$s !","%1$sPhrases consécutives%2$s : Le texte contient %4$d instances dans lesquelles %3$d phrases consécutives ou plus commencent avec le même mot. %5$sAjoutez un peu de variété%2$s !"],"%1$sPassive voice%2$s: %3$s of the sentences contain passive voice, which is more than the recommended maximum of %4$s. %5$sTry to use their active counterparts%2$s.":["%1$sVoix passive%2$s : %3$s des phrases sont à la forme passive, ce qui est au delà du ratio maximum recommandé de %4$s. %5$sPassez plutôt à la voix active%2$s."],"%1$sPassive voice%2$s: You're using enough active voice. That's great!":["%1$sVoix passive%2$s : Vous utilisez suffisamment la voix active. C’est super !"],"%1$sParagraph length%2$s: %3$d of the paragraphs contains more than the recommended maximum of %4$d words. %5$sShorten your paragraphs%2$s!":["%1$sLongueur des paragraphes%2$s : %3$d des paragraphes font plus du maximum recommandé de %4$d mots. %5$sRaccourcissez vos paragraphes%2$s !","%1$sLongueur des paragraphes%2$s : %3$d des paragraphes font plus du maximum recommandé de %4$d mots. %5$sRaccourcissez vos paragraphes%2$s !"],"%1$sParagraph length%2$s: None of the paragraphs are too long. Great job!":["%1$sLongueur des paragraphes%2$s : Aucun de vos paragraphes n’est trop long. Bon travail !"],"Good job!":["Bon travail !"],"%1$sFlesch Reading Ease%2$s: The copy scores %3$s in the test, which is considered %4$s to read. %5$s%6$s%7$s":["%1$sTest de lisibilité Flesch%2$s : Votre contenu obtient %3$s au test, ce qui est considéré comme %4$s à lire. %5$s%6$s%7$s"],"Scroll to see the preview content.":["Faites défiler pour voir la prévisualisation du contenu."],"An error occurred in the '%1$s' assessment":["Une erreur s’est produite dans l’analyse « %1$s »"],"%1$s of the words contain %2$sover %3$s syllables%4$s, which is more than the recommended maximum of %5$s.":["%1$s des mots contiennent %2$splus que %3$s syllabes%4$s, ce qui est supérieur ou égale à la valeur maximale recommandée de %5$s."],"%1$s of the words contain %2$sover %3$s syllables%4$s, which is less than or equal to the recommended maximum of %5$s.":["%1$s des mots contiennent %2$splus que %3$s syllabes%4$s, ce qui est inférieur ou égale à la valeur maximale recommandée de %5$s."],"This is slightly below the recommended minimum of %5$d word. %3$sAdd a bit more copy%4$s.":["Vous êtes est légèrement en dessous du minimum recommandé de %5$d mot. %3$sAjoutez un peu plus de contenu%4$s.","Vous êtes est légèrement en dessous du minimum recommandé de %5$d mots. %3$sAjoutez un peu plus de contenu%4$s."],"The meta description contains %1$d sentence %2$sover %3$s words%4$s. Try to shorten this sentence.":["La méta description contient %1$d phrase %2$sde plus de %3$s mots%4$s. Essayez de raccourcir cette phrase.","La méta description contient %1$d phrases %2$sde plus de %3$s mots%4$s. Essayez de raccourcir ces phrases."],"The meta description contains no sentences %1$sover %2$s words%3$s.":["La méta description ne contient aucune phrase %1$sde plus de %2$s mots%3$s."],"Mobile preview":["Prévisualisation \"Mobile\""],"Desktop preview":["Prévisualisation \"PC de bureau\""],"Please provide an SEO title by editing the snippet below.":["Merci de fournir un méta titre en modifiant le champ ci-dessous."],"Meta description preview:":["Prévisualisation de la méta description :"],"Slug preview:":["Prévisualisation du slug :"],"SEO title preview:":["Prévisualisation du méta titre :"],"Close snippet editor":["Fermer l’éditeur de métadonnées"],"Slug":["Slug"],"Remove marks in the text":["Retirer toutes les marques du texte"],"Mark this result in the text":["Marquer ce résultat dans le texte"],"Marks are disabled in current view":["Les marques sont désactivés dans la vue actuelle"],"Good SEO score":["Bon score SEO"],"OK SEO score":["Score SEO OK"],"Feedback":["Retour"],"ok":["OK"],"Please provide a meta description by editing the snippet below.":["Merci de fournir une méta description en modifiant le champ ci-dessous."],"Edit snippet":["Modifier les métadonnées"],"You can click on each element in the preview to jump to the Snippet Editor.":["Vous pouvez cliquez sur n’importe quel élément de la prévisualisation pour retourner dans l’éditeur de métadonnées."],"SEO title":["Méta titre"],"Needs improvement":["Besoin d’amélioration"],"Good":["Bon"],"very difficult":["très difficile"],"Try to make shorter sentences, using less difficult words to improve readability":["Essayez de faire des phrases plus courtes, en utilisant des mots moins compliqués afin d’améliorer la lisibilité"],"difficult":["difficile"],"Try to make shorter sentences to improve readability":["Essayez de faire des phrases plus courtes pour améliorer la lisibilité"],"fairly difficult":["assez difficile"],"OK":["OK"],"fairly easy":["assez facile"],"easy":["facile"],"very easy":["très facile"],"Meta description":["Méta description"]
            },
        },
    })
}
/*** detect note score and readabiliy */
export function noteScoreReadability(score){
    let note  = 'mauvais';
    for ( let item in ranges ) {
        if ( ranges[item]['start'] <= score && score <= ranges[item]['end'] ) {
            note  = item;
            break;
        }
    }
    return note; 
}
/** filterHtmlContent */
export  function filterHtmlContent(){
    let  pathname = window.location.pathname.split("/");
    let id_post = parseInt($('input.post_id_save').val());
    let json_order_content =function_global.postOrderVontent();
    if(id_post){
        if(get_html!==null){
            get_html.abort();
        }
        get_html =  $.ajax({
            url: "/api/admin/post/get-html/" + id_post,
            type: "POST",
            data: {
                json_order_content:JSON.stringify(json_order_content),
            },
            success: function (result) {
                console.log('result html',result);
                //result = JSON.parse(result);
                let  wrapped = $("<div>" + result.html + "</div>");
                html_filter = wrapped.html();
                $.data(document,"post_order_content_to_html", result.html); 
                analyseYaostStatics2();
            }
        });
    }
    /*if(html.length){
        let  wrapped = $("<div>" + html + "</div>");
        wrapped.find('input[type=hidden]').remove();
        wrapped.find('.element-sortable-content>button').remove();
        html = wrapped.html();
    }*/
    return html_filter;
}
export async function analyseYaostStatics2(){
    let html = html_filter;
    let seo_requete_cible = $("#seo-requete-cible").val(); 
    let seo_content_title = $("#seo-content-title").val(); 
    let seo_content_description = $("#seo-content-description").val();  
    let seo_content_slug = $("#seo-content-slug").val();
    let permalink = $("#seo-permalink").val();
    let synonymes = '';
    console.log('html',html)
    if(html.length){
        //alert(seo_content_title);
        yoastScores(html,seo_requete_cible,seo_content_title,seo_content_description,seo_content_slug,synonymes,permalink);
    }
}
/** analyse yost function */
export async function analyseYaostStatics(){
    filterHtmlContent();
}

/** set default titre length */
function setDefaultLength(element){
    if(element.val()!=undefined){
        let this_val_length  = (element.val()).length;
        let width = $('.seo-title-width').width();
        console.log('width',width);
        let purcentage = (  width/ limit_title ) * 100;
        purcentage = Math.ceil(purcentage);
        if(width>399 && width<600){
            $('.seo-content-title-progress').addClass('w3-green').removeClass('w3-red w3-orange');
        }else if(width>0 && width<=399){
            $('.seo-content-title-progress').addClass('w3-orange').removeClass('w3-red w3-green');
        }else{
            $('.seo-content-title-progress').addClass('w3-red').removeClass('w3-orange w3-green');
        }
        $('.seo-content-title-progress').css({'width':purcentage+'%'});
        $('.count-title-caractere').html(this_val_length);
        $('.seo-title-width').html(element.val());
    }
}
/** set default description length */
function setDefaultDescLength(element){
    if(element.val()!=undefined){
        let this_val_length  = (element.val()).length;
        let purcentage = ( this_val_length / limit_desc ) * 100;
        purcentage = Math.ceil(purcentage);
        if(this_val_length>119 && this_val_length<156){
            $('.seo-content-description-progress').addClass('w3-green').removeClass('w3-red w3-orange');
        }else if(this_val_length>0 && this_val_length<=119){
            $('.seo-content-description-progress').addClass('w3-orange').removeClass('w3-red w3-green');
        }else{
            $('.seo-content-description-progress').addClass('w3-red').removeClass('w3-orange w3-green');
        }
        $('.seo-content-description-progress').css({'width':purcentage+'%'});
        $('.count-desc-caractere').html(this_val_length);
    }   
}
/*** traitement */
$(document).ready(function($){
    /*let getSnippetTitle = function () {
        return (document.getElementById("seo-content-title") && document.getElementById("seo-content-title").value) || "";
    }*/
    // on change (title seo , description seo, requette cible , permalien , titre , content)
    $(document).on('change','#seo-requete-cible,#seo-content-title,#seo-content-description,#seo-content-slug',function(){
        analyseYaostStatics();
    })
    // switch between seo and readabilty
    $(".wpseo-metabox-menu a").on('click',function(e){
        e.preventDefault();
        $(this).parents('li').addClass('active').siblings('li').removeClass('active');
        let id = $(this).attr('href');
        $(id).css({'display':'block'}).siblings('div[role=tabpanel]').css({'display':'none'});
    });
    // progress bar title
    //alert((0, l.default)(getSnippetTitle()));
    setDefaultLength($("#seo-content-title"));
    $("#seo-content-title").on('keydown change keyup',function(e){
        setDefaultLength($(this));
    })
    /*$("#seo-content-title").on('change',function(e){
        setDefaultLength($(this));
    })*/
    // progress bar title
    setDefaultDescLength($("#seo-content-description"));
    $("#seo-content-description").on('keydown change keyup',function(e){
        setDefaultDescLength($(this));
    })
})