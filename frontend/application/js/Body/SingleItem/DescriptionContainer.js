export const DescriptionContainer = {
    data: function() {
        return {
            charLimit: 163,
            charLength: 0,
            showShadow: true,
            nonRevealedStyle: {
                height: '150px',
            },
            revealedStyle: {
                height: 'auto',
            }
        }
    },
    created() {
        this.charLength = this.description.length;
    },
    template: `<div class="Row DescriptionWrapper">
                   <div v-if="showShadow && charLength > charLimit" class="ShadowWrapper"></div>
                   <h1 class="DescriptionHeader">{{translationsMap.productPage.description}}</h1>
                   <p class="Description" v-bind:style="style">{{normalizedDescription}}</p>
                                
                   <p v-if="showShadow && charLength > charLimit" @click="showMoreDescription" class="MoreButton">{{translationsMap.productPage.more}}</p>
                   <p v-if="!showShadow" @click="showLessDescription" class="MoreButton">{{translationsMap.productPage.less}}</p>
               </div>`,
    props: ['description'],
    computed: {
        style: function() {
            return (this.showShadow) ? this.nonRevealedStyle : this.revealedStyle;
        },
        normalizedDescription: function() {
            if (this.charLength > 0) {
                return this.description;
            }

            return this.translationsMap.noDescription;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    methods: {
        showMoreDescription: function() {
            this.showShadow = false;
        },

        showLessDescription: function() {
            this.showShadow = true;
        }
    }
};