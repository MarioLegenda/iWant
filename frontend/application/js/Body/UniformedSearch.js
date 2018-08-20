import {SearchItem} from "./SearchItem";

export const UniformedSearch = {
    template: `<div id="uniformed_search">
                    <div class="search-box-wrapper">
                        <div class="search-box">
                            <input type="text" placeholder="Experience..."/>
                            <button><i class="fas fa-search"></i></button>
                        
                            <p>* By clicking <i class="fas fa-search"></i> you will search products from Ebay, Amazon, Etsy and Bonanza but more marketplaces will be integrated in the future. See the <router-link to="/promise">Promise</router-link> we give to you to find out more</p>
                        </div>
                    </div>

                    <div class="search-listing-wrapper wrap">
                        <div class="search-listing">
                            <SearchItem></SearchItem>
                            <SearchItem></SearchItem>
                            <SearchItem></SearchItem>
                            <SearchItem></SearchItem>
                            <SearchItem></SearchItem>
                            <SearchItem></SearchItem>
                            <SearchItem></SearchItem>
                        </div>
                    </div>
               </div>`,
    components: {
        SearchItem
    }
};