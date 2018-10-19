import {RepositoryFactory} from "../../../services/repositoryFactory";

const Description = {
    template: `
        <div class="Description" v-html="description"></div>
    `,
    props: ['description']
};

export const Categories = {
    data: function() {
        return {
            currentDescription: ``,
            items: [],
        }
    },
    created() {
        const appRepo = RepositoryFactory.create('app');

        appRepo.getNativeTaxonomies(null, (response) => {
            this.items = response.collection.data;
        });
    },
    template: `
<transition name="fade">
            <div v-if="showCategories" class="PopupMenu Categories">
                <div class="PopupMenu_Wrapper">
                
                    <div class="Wrapper-categories">
                        <a href="" class="category" v-for="(item, index) in items" :key="index" v-on:mouseover="onHover(item)">{{item.name}}</a>
                    </div>
                    
                    <div class="Wrapper-descriptions">
                        <description v-bind:description="currentDescription">
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
            this.currentDescription = item.description;
        }
    },
    components: {
        'description': Description,
    }
};