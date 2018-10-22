import {TodaysPicks} from "./TodaysPicks";
import {RepositoryFactory} from "../../services/repositoryFactory";
import {Categories} from "./Menu/Categories";
import {Shops} from "./Menu/Shops";

export const Homepage = {
    template: `<div>
                    <div id="StartPage">
                    
                        <categories-menu></categories-menu>
                        <shops-menu></shops-menu>
                        
                        <div class="StartPage-background-image">
                            <img src="/images/start_page_background.jpg"/>
                        </div>
                   
                         <div class="StartPage_MissionStatement">
                             <h1 class="MissionStatement-header">Our mission</h1>
                             <div class="MissionStatement_Content">
                                 <span class="Content-full-element-span">To provide you with a unique shopping experience with products from </span>
                                 <span class="Content-full-element-span" id="typed_js_sentence"></span> online marketplace(s). <a href="" class="MissionStatement_Link">Read more</a> about it.
                             </div>
                         </div>
                     </div>
               </div>`,
    created() {
        if (isObjectEmpty(this.$store.state.todaysProductsListing)) {
            const todayProductsRepository = RepositoryFactory.create('todays-products');

            const date = new Date();
            todayProductsRepository.getTodaysProducts(
                `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`,
                (response) => {
                    this.$store.commit('todaysProductsListing', response.collection.data);
                }
            );
        }
    },
    components: {
        'todays-picks': TodaysPicks,
        'categories-menu': Categories,
        'shops-menu': Shops,
    }
};