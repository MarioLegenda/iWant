import {IWouldLike} from "../global/IWouldLike";

export const Guide = {
    template: `
        <transition name="fade">
            <div class="Guide">
                <div class="TextPanel">
                    <div class="TextBlock">
                        <h1>Introduction</h1>
                        
                        <p>
                            Before I start, it is important to say that this 
                            is not a feature list or an explanation of all the features 
                            this application has. Here, I will explain how a certain 
                            feature works. If you want to know why a certain feature works 
                            as it works, check out the <router-link :to="createStaticUrl('features')">Features
                            </router-link> section.
                            And as with all guides, this one will be interesting but also boring 
                            in some parts, but it is very important that you read it because it may
                            help you a lot in finding what you want in eBay. 
                        </p>
                        
                        <p>
                            Some of you may also think that a guide is not necessary because 
                            all of us use Google and we already know how to search for 
                            things on the internet. Many websites have their own search 
                            feature that allows us to search their website and most of them
                            are pretty simple to use. But this search engine is not the same as
                            most of them. On <i-would-like /> you have the ability to search most
                            of eBay sites (more of them are coming) and, in order to make the search
                            more accessible to as many people as possible, you can only search one 
                            eBay site at a time. As explained in the <router-link :to="createStaticUrl('features')">Features
                            </router-link> section, this is not just a copy of eBay search; it's an extension
                            of it. Therefor, I have implemented features that eBay does not have and more
                            of them are coming in the future.
                        </p>
                        
                        <p>
                            Because of that, I advise you to not just skim trough this guide but to read it, try some
                            examples by yourself and maybe find some things that the application can do that even
                            I don't know about. At the end of this guide, you can check out the 
                            <router-link :to="createStaticUrl('for-you')">for You</router-link> section
                            to see what new features are awaiting for you in the future. Also, there is a proof of concept
                            and the end of this guide that proves that certain features work as this guide says.
                        </p>
                    </div>
                                        
                    <div class="TextBlock">
                        <h1>Sorting</h1>
                        
                        <div class="DisplayImage">
                            <img src="/images/application_static/filters.png" />
                        </div>
                        
                        <p>
                            Sorting allows you to sort the results by price and quality but it does not work
                            the same as on eBay. If you sort by lowest price, the search will try to return only
                            the relevant results that you actually want and not the ones that you don't want.
                            For example, if you search for an <span class="Highlight">iphone 7</span> on eBay and sort it
                            by lowest price, the first results will be iPhone masks or other utilities, and it could also
                            include auction listings which is not everybodies desire. What you actually want is a list
                            of Apple Iphone 7 smartphones. If you sort by lowest price on 
                            <i-would-like />, it will try to sort the results and give the lowest price of the actual
                            <span class="Highlight">iphone 7</span> and ignore the rest. It may not always work the way you
                            expect but there are other features that you can use to sort the results even more and get
                            what you want. More on that later in other chapters.
                        </p>
                        
                        <p>
                            Sorting by highest price works the same way as sorting by lowest price. Sorting by fixed price
                            tries to filter out only the items that have a fixed price (items that are not auctions). This feature
                            is also one of the features that is not supported by eBay. The last
                            way you can sort is by high quality. The reason why I putted only one quality sort is that
                            the condition of the item combined with the aspects of the item can be a very powerful way of finding the
                            exact items that you are looking for. For that reason, I will wait for you to tell me your wishes about this
                            and see where I will go from there.
                        </p>
                    </div>
                    
                    <div class="TextBlock">
                        <h1>Modifying the results</h1>
                        
                        <div class="DisplayImage">
                            <img src="/images/application_static/modifiers.png" />
                        </div>
                        
                        <p>
                            While sorting the results allows you to sort them in a certain way, modifying
                            them means that you put in or take out some items from the search result. It is best
                            to explain them one by one to see the big picture behind them.
                        </p>
                        
                        <h2 class="InnerEntry">Duplicated items</h2>
                        
                        <p class="InnerEntry">
                            When putting an item on eBay, some sellers put more than one entry of the same item.
                            That increases the possibility of placing their item on the top of the search. The problem
                            for the end user, but also for other sellers and resellers is that they have to sim trough a lot
                            of items before they find the one(s) they are looking for. If you click on the 
                            <span class="Highlight">Show duplicates</span>, that will turn on the duplicates hiding, the
                            listing will reload and the duplicates will disappear. This is also one of the features that
                            eBay does not provide.
                        </p>
                        
                        <h2 class="InnerEntry">Including eBay site native language items</h2>
                        
                        <p class="InnerEntry">
                            If you try searching items on eBay Germany, you will see that the site is in german including
                            the item listings. But if you try to search it on english, there are some items that are found.
                            That is because some sellers put items on german language only, some on english only and some on both.
                            Because of that, your search query might not return all the relevant results. If you tick the 
                            <span class="Highlight">Only english</span> button, searching both english and the language of the
                            site will be turned on.
                        </p>
                        
                        <p class="InnerEntry">
                            <i-would-like /> will translate the search query to the language of the eBay site that you wish to search
                            and conduct the search in both english and the eBay sites language. For example, if you search for a 
                            <span class="Highlight">pocket watch</span> on eBay Italy, <i-would-like /> will translate that to
                            <span class="Highlight">orologio da tasca</span> under the hood and do two searches; one in english and one in italian.
                            After that, it will filter out the items that are identical and display the search results for you.
                        </p>
                        
                        <p class="InnerEntry">
                            NOTE: <span class="Highlight">This feature is still experimental and I still haven't decided is it actually
                            useful for you. It also takes a really long time since the application has to do multiple queries for each
                            query translation. Please, don't hesitate to check out the 
                            <router-link :to="createStaticUrl('for-you')">For you</router-link> section and tell me what you think.
                            </span>
                        </p>
                        
                        <h2 class="InnerEntry">SQF or Search query filter</h2>
                            
                        <p class="InnerEntry">
                            If you tried the above example with <span class="Highlight">iphone 7</span> sorted by lowest price,
                            you have noticed that the final search result still has some items that are not an actual iPhone 7 smartphone.
                            The reason for that is of technical nature. When the application asks for every iPhone 7 smartphone, eBay
                            searches their database for all occurrences of a string of<span class="Highlight">iphone 7</span> characters
                            in the title of each item. That means that the final result set will have items like iphone 7 masks,
                            protective cases, earpieces or similar items. 
                        </p>
                        
                        <p class="InnerEntry">
                            Because of that reason, I have a list of common phrases that are not valid for a search for iphone smartphones.
                            One of them is the word <span class="Highlight">mask or masks (plural)</span>. When you search for an iPhone,
                            the title of each found item is searched for that word and excluded from the final search result. Other phrases include
                            <span class="Highlight">screen, shell, adapter, cable</span> and similar words. That means if you use this modifier,
                            you will get the most accurate search results for an iPhone. SQF also includes other smartphone manufacturers like Samsung,
                            Huawei, ZTE and others. 
                        </p>
                        
                        <p class="InnerEntry">
                            But that doesn't mean that you cannot search for an actual <span class="Highlight">iphone 7 mask</span>. If the application
                            determines that there is a word in the search query that has also been excluded, it does not implement SQF on
                            it. 
                        </p>
                    </div>
                    
                    <div class="TextBlock">
                        <h1>Translations</h1>
                        
                        <p>
                            Everything in <i-would-like /> is translated to multiple languages. You can see the list of languages
                            that are supported by clicking on the button in the upper right corner.
                        </p>
                        
                        <div class="DisplayImage">
                            <img src="/images/application_static/languageBar.png" />
                        </div>
                        
                        <p>
                            Because of the fact that everything is translated, you can choose any eBay site currently supported and 
                            it will translate it for you to the language of your choice. In practice, if you choose your site language
                            to be french and search eBay Germany, the search results will be translated from german to french. This is 
                            valid for every language that is supported.
                        </p>
                        
                        <p>
                            There is also one hidden feature that is enabled by default. If you search, for example 
                            <span class="Highlight">orologio da tasca</span>, and choose eBay Great Britain, the search
                            query will be translated to english and the search will be made in english. This feature is enabled
                            for every language, not just the supported site languages.
                        </p>
                    </div>
                    
                    <div class="TextBlock">
                        <h1>Proof of concept</h1>
                        
                        <p>
                            So, how to combine all of these features to find what you are looking for on eBay?
                            In this part, I will try to show you using <i-would-like /> on a practical example
                            Since I have used the <span class="Highlight">iphone 7</span> example throughout this 
                            guide, I will use it here because it best describes the power of searching on <i-would-like />
                        </p>
                        
                        <p>
                            A practical question that someone might ask is:
                        </p>
                        
                        <p>
                            <span class="Highlight">"I would like an iphone 7 with a fixed price sorted by lowest price first."</span>
                        </p>
                        
                        <p>
                            To make the most of <i-would-like />, type in your search in the search bar, choose 
                            <span class="Highlight">Lowest price</span> and <span class="Highlight">Fixed price</span>
                            sorting. After that, click on the <span class="Highlight">Show duplicates</span> toggle 
                            button which hides duplicated items. After that, click <span class="Highlight">SQF off</span>
                            which will turn SQF on. Click the search button and choose any eBay site that you want.
                        </p>
                        
                        <p>
                            Now try to do the same on the actual eBay site that you searched and you will see that 
                            <i-would-like /> has much finer grained result set than the actual eBay. Try to play with it some
                            more and, if there is a feature that you would like me to implement, check out the 
                            <router-link :to="createStaticUrl('for-you')">for You</router-link> section
                            and tell me about it. I am always glad to hear from you.
                        </p>
                    </div>
                    
                </div>
            </div>
</transition>
    `,
    methods: {
        createStaticUrl(path)  {
            return `/${this.$localeInfo.locale}/${path}`;
        }
    },
    components: {
        'i-would-like': IWouldLike,
    }
};