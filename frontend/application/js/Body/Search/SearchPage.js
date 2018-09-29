import {Categories} from "../Homepage/Menu/Categories";
import {Shops} from "../Homepage/Menu/Shops";
import {AdvancedSearch} from "./AdvancedSearch";

export const SearchPage = {
    template: `<div id="search_page">
                    <categories-menu></categories-menu>
                    <shops-menu></shops-menu>
                    
                    <advanced-search></advanced-search>
               </div>`,
    components: {
        'categories-menu': Categories,
        'shops-menu': Shops,
        'advanced-search': AdvancedSearch
    }
};