export const Menu = {
    data: function() {
        return {
            showCategories: false,
            showShops: false,
        }
    },
    template: `<div id="main_menu">
                   <nav>
                        <div class="menu-item category-menu-item">
                            <a href="#" v-on:click.stop="showCategoriesMenu">Categories</a>
                        </div>
                        
                        <div class="menu-item">
                            <a href="#">Tickets</a>
                        </div>
                        
                        <div class="menu-item">
                            <a href="#" v-on:click.stop="showShopsMenu">Shops</a>
                        </div>
                        
                        <div class="menu-item">
                            <a href="#">Our mission</a>
                        </div>
                   </nav>
               </div>`,
    methods: {
        showCategoriesMenu() {
            this.$store.commit('showCategories', !this.$store.state.showCategories);

            this.$store.commit('showShops', false);
            this.showShops = false;
        },
        showShopsMenu() {
            this.$store.commit('showShops', !this.$store.state.showShops);

            this.$store.commit('showCategories', false);
            this.showCategories = false;
        }
    }
};