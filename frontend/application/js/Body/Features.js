import {IWouldLike} from "../global/IWouldLike";

export const Features = {
    template: `
               <transition name="fade">
                   <div class="TextPanel">
                       <div class="TextBlock">
                           <p>
                               <i-would-like></i-would-like> is created on an idea to make searching and using eBay easier, informative and more user focused. It offers features that eBay does not offer but also expands on existing ones. In that respect, iwouldlike is always growing. Here, you will find descriptions of visible and automatic features. Visible are the ones that you see on the search page and can control. Automatic ones are the ones that are applied automatically and you cannot control them (although, it might be possible in the future is you wish so). So, let’s dive in and see how iwouldlike features can help you make your life easier in using eBay. If you have any new ideas or suggestions after reading this section, please check out the <a href="#">for You</a> section and I will be more than glad to talk to you about them.
                           </p>
                           
                           <p><span class="Highlight">Visible</span> features are the ones that you see on the search page like <span class="Highlight"></span> or <span class="Highlight">brand search</span>. They are self explanatory and I will not dive into them right now. If you wish to know how to use them efficiently, checkout out the <a href="#">Guide</a> section for more information</p>
                           
                           <p><span class="Highlight">Automatic</span> features are the ones that are automatically applied to your search query. For now, the only automatic feature is the automatic translation of a search query if that search query is not in english but more of them will come. For example, if you search for a <span class="Highlight">maceta de jardín</span> (translated from spanish means <span class="Highlight">garden hose</span>), the application will detect the language as spanish, translate it to english and then conduct the search.</p>
                       </div>
                       
                       <div class="TextBlock">
                           <h1>Brand search</h1>
                           
                           <p>Brand search searches eBay for only hand picked, top eBay sellers and shops that ship items worldwide and with affordable prices. This search allows you to search for everything from electronics and gadgets to musical instruments and antiques. You can checkout the full list of all shops and sellers in the <a href="#">Brands</a> section.</p>
                           
                           <p>For example, when you search for an <span class="Highlight">iphone 7</span>, what you actually want is an <span class="Highlight">Apple Iphone 7 smartphone</span>. If you search the entire eBay, the search might give you iphone 7 masks, holders or pendants; things that you don't actually want.</p>
                           
                           <p>With brand search enabled, you get what you actually want and what you actually searched for.</p>
                       </div>
                       
                       <div class="TextBlock">
                           <h1>Hiding duplicates</h1>
                           
                           <p>Some of the items listed on eBay are duplicates of the same item. You have an option of hiding those items in your search listing.</p>
                       </div>
                       
                       <div class="TextBlock">
                           <h1>Price filters</h1>
                           
                           <p>For now, there are 3 price filters: <span class="Highlight">lowest price</span>, <span class="Highlight">highest price</span> and <span class="Highlight">fixed price</span>. What is interesting about them is that they do not sort results as eBay does. eBay sorts results with lowest price of all of their items combined. </p>
                       </div>
                   </div>
               </transition>
`,
    components: {
        'i-would-like': IWouldLike
    }
};