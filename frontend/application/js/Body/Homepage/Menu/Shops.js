const Description = {
    template: `
        <div class="Description" v-html="template"></div>
    `,
    props: ['template']
};

export const Shops = {
    data: function() {
        return {
            currentTemplate: ``,
            items: [
                {
                    name: 'Ebay',
                    template: `<div>Books, Music & MoviesLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Etsy',
                    template: `<div>Autoparts & MechanicsLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Amazon',
                    template: `<div>Home & GardenLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
            ]
        }
    },
    template: `
<transition name="fade">
            <div v-if="showShops" class="PopupMenu Shops">
                <div class="PopupMenu_Wrapper">
                    <div class="Wrapper-shops">
                        <a href="" class="shop" v-for="(item, index) in items" :key="index" v-on:mouseover="onHover(item)">{{item.name}}</a>
                    </div>
                    
                    <div class="Wrapper-descriptions">
                        <description v-bind:template="currentTemplate">
                        </description>
                    </div>
                </div>
            </div>
</transition>

    `,
    computed: {
        showShops: function() {
            return this.$store.state.showShops;
        }
    },
    methods: {
        onHover: function(item) {
            this.currentTemplate = item.template;
        }
    },
    components: {
        'description': Description,
    }
};