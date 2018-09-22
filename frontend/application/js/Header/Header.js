export const Header = {
    template: `<div id="main_header">
                   <header>
                       <router-link to="/" class="logo" exact>
                           <span class="logo-part i-part">i</span>
                           <span class="logo-part am-part">Would</span>
                           <span class="logo-part buying-part">Like</span>
                       </router-link>
                      
                       <div class="SearchBox">
                           <div class="SearchBox_InputBox">
                                <input type="text" placeholder="search what would you like" />
                           </div>
                           
                           <div class="SearchBox_SubmitBox">
                                <button><i class="fas fa-chevron-right"></i></button>
                           </div>
                           
                           <div class="SearchBox_AdvancedSearch">
                               <a href="">Advanced search <i class="fas fa-search"></i></a>
                           </div>
                       </div>
                   </header>
               </div>`
};
