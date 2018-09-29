export const SearchBox = {
    template: `<div class="SearchBox">
                           <div class="SearchBox_InputBox">
                                <input type="text" placeholder="what would you like?" />
                           </div>
                           
                           <div class="SearchBox_SubmitBox">
                                <button><i class="fas fa-chevron-right"></i></button>
                           </div>
                           
                           <div class="SearchBox_AdvancedSearch">
                               <router-link to="/search">Advanced search <i class="fas fa-search"></router-link>
                           </div>
               </div>`
};