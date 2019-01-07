import {IWouldLike} from "../global/IWouldLike";

export const Features = {
    template: `
               <transition name="fade">
               <div class="Features">
                   <div class="TextPanel Features">
                       <div class="TextBlock">
                           <h1>Introduction</h1>
                           
                           <p>
                               <i-would-like></i-would-like> is created on an idea to make searching 
                               and using eBay easier, informative, user focused and accessible to everyone, no
                               matter the language they speak. Entire website and every eBay listing can
                               be translated to multiple languages so it is no longer necessary for you to
                               know english in order to use eBay. It also offers search
                               features that eBay does not offer but also expands on existing ones. 
                               In that respect, <i-would-like></i-would-like> is always growing. Here, you will find 
                               descriptions of visible and automatic features. Visible are the ones 
                               that you see on the search page and can control. Automatic ones are 
                               the ones that are applied automatically and you cannot control them 
                               (although, it might be possible in the future is you wish so). 
                               If you have any new ideas or suggestions 
                               after reading this section, please check out the <a href="#">for You</a> 
                               section and I will be more than glad to talk to you about them.
                           </p>
                           
                           <p>The application uses eBay API (you can checkout this 
                           <a href="https://en.wikipedia.org/wiki/Application_programming_interface">wikipedia entry</a> 
                           if you want to know more about the technical side of the application) to query the data, 
                           process it and display it for you. Because of that reason, it is somewhat limited. 
                           The API allows querying only 100 items at a time, which means that the application 
                           cannot know about all the results of the query. I will return to that limitation 
                           later in other sections. There is also the limitation on the number of queries that
                           I am allowed to make per day. Therefor, most of the information is saved the first time I
                           query the eBay API and then used again for the next 24 hours. Put more simply, the items information
                           gets refreshed every 24 hours. This is not a subject that could be explained easily so 
                           if you have any questions, go to the <a href="">for You</a> section and ask me any question
                           you want. Let's get back to <span class="Highlight">visible</span> and <span class="Highlight">automatic</span>
                           features.</p>
                           
                           <p><span class="Highlight">Visible</span> features are the ones that 
                           you see on the search page like <span class="Highlight"></span> or 
                           <span class="Highlight">brand search</span>. They are self explanatory and I 
                           will not dive into them right now. If you wish to know how to use them efficiently, 
                           checkout out the <a href="#">Guide</a> section for more information</p>
                           
                           <p><span class="Highlight">Automatic</span> features are the ones that are 
                           automatically applied to your search query. For now, the only automatic 
                           feature is the automatic translation of a search query if that search query 
                           is not in english but more of them will come. For example, if you search 
                           for a <span class="Highlight">maceta de jardín</span> (translated from 
                           spanish means <span class="Highlight">garden hose</span>), the application 
                           will detect the language as spanish, translate it to english and then 
                           conduct the search.</p>
                       </div>

                       <div class="TextBlock">
                           <h1>Hiding duplicates</h1>
                           
                           <p>Some of the items listed on eBay are duplicates of the same item. You 
                           have an option of hiding those items in your search listing.</p>
                       </div>
                       
                       <div class="TextBlock">
                           <h1>Price filters</h1>
                           
                           <p>For now, there are 3 price filters: <span class="Highlight">lowest price</span>, 
                           <span class="Highlight">highest price</span> and <span class="Highlight">fixed price</span>. 
                           What is interesting about them is that they do not sort results as eBay does. 
                           eBay sorts results with lowest price of all of their items combined. 
                           That means if you search for an <span class="Highlight">iphone 7</span> you might 
                           get over 3000 items. If you sort them by lowest price, eBay will sort all those results by 
                           lowest price. That is why you get all those iphone masks when you actually wanted and Iphone 7
                           smartphone. <i-would-like></i-would-like> gets the information by the sorting order that you specify
                           and sorts items in that order. That means if you sort items by <span class="Highlight">best match</span>
                           and filter them by lowest price, you will get the lowest price of best matched items. That will
                           create a listing can actually displays the lowest price of all the <span class="Highlight">Iphone 7 smartphones</span>
                           and minimizes the redundant unwanted listing results like iphone masks or cases.</p>
                       </div>
                       
                       <div class="TextBlock">
                           <h1>Modifiers</h1>
                           
                           <p>
                               Modifiers are designed to modify the search results with certain custom functionality.
                               Hiding duplicates is one of them and it is already explained above. There is also the
                               <span class="Highlight">double language search</span> and <span class="Highlight">SQF (Search query filter)</span>
                           </p>
                           
                           <p>
                               <span class="Highlight">Double locale search</span> is designed to search for items in both english
                               and the site native language. The problem that I wanted to solve with this feature is that some sellers
                               upload items in the native language of the site that they are using while others upload it on
                               english. The consequence of that is that when you are searching, for example eBay Spain, you search
                               it on english and list only items that were uploaded on english. If you tried the same search for the same
                               item on spanish, it would give results that wouldn't give you on english.
                           </p>
                           
                           <p>
                               For that reason, this feature translates your search query to the native language of the site that
                               you are searching and searches eBay both on that language and english. For example, is you search for
                               a <span class="Highlight">pocket watch</span> on eBay Spain, <i-would-like /> will translate that term
                               to <span class="Highlight">reloj de bolsillo</span> and search eBay Spain with that query. After that, it
                               will search the same site on english and present the final result to you. That broadens the amount of items
                               that you can find with just one search
                           </p>
                       </div>
                       
                       <div class="TextBlock">
                           <h1>Translations</h1>
                           
                           <p>
                               Everything on <i-would-like></i-would-like> is translated, including
                               the eBay listings and all the information of an eBay product. That means that a listing on eBay Germany will be translated
                               to the language that you choose. The default language is English, but you
                               can choose between others like French, Italian, Czech and many others.
                           </p>
                          
                           <p>
                               Also, if you make your search query on any language other than english,
                               that search query will be translated to english and the query to eBay will be
                               done on english. For example, if you search for a <span class="Highlight">reloj a prueba de agua</span>,
                               the application will translate it to english (which means <span class="Highlight">water proof watch</span>),
                               and query eBay for it. That means you can use eBay on any language that you wish.
                           </p>
                           
                           <p>
                               There are also many items that are listed only in the language of the eBay site that 
                               the seller is registered on. Many items on eBay Germany or eBay Spain are on German and
                               Spanish only and do not appear on searches made in English. The application gives you an
                               option to search for both search terms, on english and on the sites language. To expand on the
                               previous example of the water proof watch, the search will make a query in english and in spanish,
                               if search on eBay Spain, or on German, if searching on eBay Germany. That gives you a much
                               broader selection of products than you would have in searching only in English.
                           </p>
                           
                           <p>
                               Please, bear in mind that all the translation features are experimental and might
                               be subject to change. Translations of eBay data is stable, but much work needs to 
                               be done to make the entire application translatable to every language in the world.
                           </p>
                       </div>
                   </div>
               </div>
               </transition>
`,
    components: {
        'i-would-like': IWouldLike
    }
};