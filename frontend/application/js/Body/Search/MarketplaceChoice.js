import {RepositoryFactory} from "../../services/repositoryFactory";

export const MarketplaceChoice = {
    data: function() {
        return {
            ebayGlobalIdChoice: false,
            ebayGlobalIdChoices: [],
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
                            v-for="(siteInfo, index) in ebayGlobalIdChoices"
                            :key="index"
                            v-bind:class="{'ClickableElement-highlighted': marketplaces.etsy }" 
                            class="ClickableElement EbaySiteSelection" 
                            @click="showEbaySite(siteInfo.globalId)">{{siteInfo.siteName}}<img :src="siteInfo.flag" />
                        </p>
                    </div>
                </div>
            </div>
    `,
    methods: {
        showEtsy() {
            this.disableAllButtons();

            this.marketplaces.etsy = true;
            this.ebayGlobalIdChoice = false;

            this.emitEvent({
                marketplace: 'etsy',
                globalId: null,
            });
        },

        showEbaySite(globalId) {
            this.emitEvent({
                marketplace: 'ebay',
                globalId: globalId,
            });
        },

        showEbayGlobalIdChoice() {
            this.disableAllButtons();

            this.marketplaces.ebay = true;

            const appRepo = RepositoryFactory.create('app');

            appRepo.getCountries(null, (response) => {
                const countries = response.collection.data;

                this.ebayGlobalIdChoices = this.ebayGlobalIds.map((globalIdInfo) => {
                    const foundCountry = countries.filter((c) => c.alpha2Code === globalIdInfo.alpha2Code);

                    return {
                        globalId: globalIdInfo.global_id,
                        siteName: globalIdInfo.site_name,
                        flag: foundCountry[0].flag,
                    };
                });

                this.ebayGlobalIdChoice = !this.ebayGlobalIdChoice;
            });
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