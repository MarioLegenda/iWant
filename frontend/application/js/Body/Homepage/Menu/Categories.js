const Description = {
    template: `
        <div class="Description" v-html="template"></div>
    `,
    props: ['template']
};

export const Categories = {
    data: function() {
        return {
            currentTemplate: ``,
            items: [
                {
                    name: 'Books, Music & Movies',
                    template: `<div>Books, Music & MoviesLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Autoparts & Mechanics',
                    template: `<div>Autoparts & MechanicsLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Home & Garden',
                    template: `<div>Home & GardenLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Computers, Mobile & Games',
                    template: `<div>Computers, Mobile & GamesLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Sport',
                    template: `<div>SportLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Antiques, Art & Collectibles',
                    template: `<div>Antiques, Art & CollectiblesLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
                {
                    name: 'Crafts & Handmade',
                    template: `<div>Crafts & HandmadeLorem ipsum dolor sit amet, consectetur adipiscing elit. Ut varius mauris vitae enim ornare maximus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas congue suscipit odio at euismod. Cras eget metus nisl. In et leo nec lacus rhoncus imperdiet. Mauris ultricies augue sapien, id fringilla mi auctor sit amet.</div>`
                },
            ]
        }
    },
    template: `
<transition name="fade">
            <div v-if="showCategories" class="PopupMenu Categories">
                <div class="PopupMenu_Wrapper">
                    <div class="Wrapper-categories">
                        <a href="" class="category" v-for="(item, index) in items" :key="index" v-on:mouseover="onHover(item)">{{item.name}}</a>
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
        showCategories: function() {
            return this.$store.state.showCategories;
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