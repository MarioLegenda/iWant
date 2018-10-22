import {RepositoryFactory} from "../../services/repositoryFactory";

export const MarketplaceChoice = {
    data: function() {
        return {
            ebayGlobalIdChoice: false,
            marketplaces: {
                ebay: false,
                etsy: false,
            },
            ebayImg: '/images/temp_ebay_logo.png',
            etsyImg: '/images/temp_etsy_logo.png',
        }
    },
    props: ['ebayGlobalIds'],
    template: `
            <div class="MarketplaceChoice">
                <div class="ChoiceButtons">
                    <h1>Choose your marketplace</h1>
                    
                    <div class="ChoiceCaret">
                        <i class="fas fa-long-arrow-alt-down"></i>
                    </div>
                    
                    <div class="MarketplaceButtonWrapper">
                        <p 
                            v-bind:class="{'ClickableElement-highlighted': marketplaces.ebay }" 
                            class="ClickableElement" 
                            @click="showEbayGlobalIdChoice"><img alt="Choose eBay" :src="ebayImg" />
                        </p>
                        
                        <p 
                            v-bind:class="{'ClickableElement-highlighted': marketplaces.etsy }" 
                            class="ClickableElement" 
                            @click="showEtsy"><img alt="Choose Etsy" :src="etsyImg" />
                        </p>
                    </div>
                    
                    <div v-if="ebayGlobalIdChoice" class="MarketplaceButtonWrapper">
                        <div class="ChoiceCaret">
                            <i class="fas fa-long-arrow-alt-down"></i>
                        </div>
                        
                        <p 
                            v-for="(globalId, index) in ebayGlobalIdChoices"
                            :key="index"
                            v-bind:class="{'ClickableElement-highlighted': marketplaces.etsy }" 
                            class="ClickableElement" 
                            @click="showEtsy"><img alt="Choose Etsy" :src="etsyImg" />
                        </p>
                    </div>
                </div>
            </div>
    `,
    computed: {
        ebayGlobalIdChoices: function() {
            const appRepo = RepositoryFactory.create('app');

            appRepo.getCountries(null, (response) => {
                const items = response.collection.data;

                console.log(this.ebayGlobalIds);
            });
        }
    },
    methods: {
        showEtsy() {
            this.disableAllButtons();

            this.marketplaces.etsy = true;

            this.emitEvent({
                marketplace: 'etsy',
                globalId: null,
            });
        },

        showEbayGlobalIdChoice() {
            this.disableAllButtons();

            this.ebayGlobalIdChoice = !this.ebayGlobalIdChoice;
        },

        emitEvent(data) {
            this.$emit('on-choice', data);
        },

        disableAllButtons() {
            for (let marketplace in this.marketplaces) {
                if (this.marketplaces.hasOwnProperty(marketplace)) {
                    this.marketplaces[marketplace] = false;
                }
            }
        }
    }
};