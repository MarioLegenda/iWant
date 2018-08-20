export const Header = {
    template: `<div id="main_header">
                   <header>
                       <router-link to="/" class="logo" exact>
                           <span class="logo-part i-part">i</span>
                           <span class="logo-part am-part">am</span>
                           <span class="logo-part buying-part">buying</span>
                       </router-link>
                       
                       <nav>
                           <router-link to="/search" exact>SEARCH
                                <i class="fas fa-search"></i>
                                <span></span>
                           </router-link>
                           
                           <a href="/for-investors">COMPARE
                                <i class="fas fa-clone"></i>
                                <span></span>
                           </a>
                           
                           <a href="/tickets">TICKETS
                                <i class="fas fa-ticket-alt"></i>
                                <span></span>
                           </a>
                       </nav>
                   </header>
               </div>`
};
