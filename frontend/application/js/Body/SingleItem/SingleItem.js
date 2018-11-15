import {RepositoryFactory} from "../../services/repositoryFactory";
import {Price} from "../../services/util";

const ActionNameValueContainer = {
    data: function() {
        return {
            showDescription: false
        }
    },
    template: `<div @click="onShowDescription" class="Row NameValueContainer IsHoverable">
                   <p class="Name">{{name}}</p>
                   <p class="Value">{{value}}</p>
                                
                   <i v-bind:class="toggleChevronClass"></i>
                   <transition name="fade">
                       <p v-if="showDescription && description !== false" class="NameValueDescription">{{description}}</p>
                   </transition>
                   
                   <transition name="fade">
                       <slot v-if="showDescription" name="description"></slot>
                   </transition>
               </div>`,
    props: ['name', 'value', 'description'],
    computed: {
        toggleChevronClass: function() {
            return (this.showDescription === false) ? 'ActionIdentifier fas fa-chevron-right' : 'ActionIdentifier fas fa-chevron-down'
        }
    },
    methods: {
        onShowDescription() {
            this.showDescription = !this.showDescription;
        }
    }
};

const NameValueContainer = {
    template: `<div class="Row NameValueContainer">
                   <p class="Name">{{name}}</p>
                   <p class="Value">{{value}}</p>
               </div>`,
    props: ['name', 'value']
};

const DescriptionContainer = {
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

const ItemLoader = {
    template: `
        <div class="ItemLoaderWrapper">
            <p class="ItemLoader">{{translationsMap.productPage.loadingProduct}}</p>
        </div>
    `,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    }
};


export const SingleItem = {
    data: function() {
        return {
            item: null,
        }
    },
    template: `<transition name="fade"><div class="SingleItemWrapper">

                    <item-loader v-if="item === null"></item-loader>
                    
                    <div v-if="item" class="SingleItem">
                    
                        <div class="Panel RightPanel">
                            <div class="Row TitleWrapper">
                                <h1 class="Title">{{item.title}}</h1>
                            </div>
                            
                            <div class="Row ViewsWrapper LightSeparator">
                                <p>{{item.hitCount}} {{translationsMap.productPage.views}}</p>
                            </div>
                            
                            <div class="Row PriceWrapper">
                                <price 
                                    v-bind:currency="item.priceInfo.convertedCurrentPriceId"
                                    v-bind:price="item.priceInfo.convertedCurrentPrice" >
                                </price>
                                
                                <price
                                    v-bind:currency="item.priceInfo.currentPriceId"
                                    v-bind:price="item.priceInfo.currentPrice" >
                                </price>
                            </div>
                            
                            <div class="Row ShippingOptionsWrapper">
                                <button>{{translationsMap.productPage.viewShippingDetails}}<i class="fas fa-truck"></i></button>
                            </div>
                            
                            <div class="CenterPanel Border"></div>
                            
                            <name-value-container
                                v-bind:name="translationsMap.productPage.endsOn"
                                v-bind:value="item.endTime | userFriendlyDate">
                            
                            </name-value-container>
                            
                            <name-value-container
                                v-bind:name="translationsMap.productPage.isAuction"
                                v-bind:value="(item.bidCount !== 0) ? translationsMap.yes : translationsMap.no">
                            </name-value-container>
                            
                            <name-value-container
                                v-bind:name="translationsMap.productPage.handlingTime"
                                v-bind:value="parseHandlingTimeString(item.handlingTime)">
                            </name-value-container>
                            
                            <name-value-container
                                v-bind:name="translationsMap.productPage.condition"
                                v-bind:value="item.conditionDisplayName">
                            </name-value-container>
                            
                            <action-name-value-container 
                                v-bind:name="translationsMap.productPage.requiresImmediatePayment"
                                v-bind:value="(item.autoPay === true) ? translationsMap.yes : translationsMap.no"
                                description="The seller requires immediate payment for the item. Buyers must have a PayPal account to purchase items that require immediate payment">
                            </action-name-value-container>
                            
                            <action-name-value-container
                                v-bind:name="translationsMap.productPage.bestOfferFeatureEnabled"
                                v-bind:value="(item.bestOfferEnabled === true) ? translationsMap.yes : translationsMap.no"
                                v-bind:description="false">
                                
                                <div slot="description">
                                    <p class="NameValueDescription">This feature indicates whether the seller will accept a Best Offer for the item. The Best Offer feature allows a buyer to make a lower-priced, binding offer on an item. Buyers can't see how many offers have been made (only the seller can see this information)</p>
                                    <p class="NameValueDescription">The Best Offer feature has not been available for auction listings, but beginning with Version 1027, sellers in the US, UK, and DE sites will be able to offer the Best Offer feature in auction listings. The seller can offer Buy It Now or Best Offer in an auction listing, but not both.</p>
                                </div>
                                
                            </action-name-value-container>
                            
                            <div class="Row ViewOnEbayButtonWrapper">
                                <a :href="item.viewItemUrlForNaturalSearch" target="_blank">{{translationsMap.productPage.viewOnEbay}}<i class="fas fa fa-link"></i></a>
                            </div>
                           
                        </div>
                        
                        <div class="Panel LeftPanel">
                            <div class="Row Seller">
                                <h1>{{item.seller.userId}}</h1>
                                <span>({{item.seller.feedbackScore}})</span>
                                <p>{{translationsMap.productPage.positiveFeedbackScore}}<span>{{sellerFeedbackPercent}}%</span></p>
                            </div>
                            
                            <div class="Row ThumbnailImageWrapper">
                                <img class="Image" :src="parsePictureUrl(item.pictureUrl)" />
                            </div>
                            
                            <description-container v-bind:description="item.description"></description-container>
                        </div>
                    </div>

               </div></transition>`,
    created() {
        if (this.item === null) {
            const singleItemRepo = RepositoryFactory.create('single-item');

            const paths = window.location.pathname.split('/');

            const itemId = paths[4];

            singleItemRepo.getSingleItem({
                locale: this.$localeInfo.locale,
                itemId: itemId,
            }, (r) => {
                this.item = r.resource.data;
                console.log(this.item);
            });
        }
    },
    computed: {
        sellerFeedbackPercent: function() {
            const feedbackPercent = this.item.seller.positiveFeedbackPercent;

            if (feedbackPercent === '100.0') {
                return 100;
            }

            return feedbackPercent;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    methods: {
        parsePictureUrl(pictureUrl) {
            if (pictureUrl !== null && typeof pictureUrl !== 'undefined') {
                const regex = /\$_\d+/g;

                return pictureUrl.replace(regex, '$_1');
            }
        },

        parseHandlingTimeString(handlingTime) {
            return `Ships within ${handlingTime} days upon receiving a clear payment`
        }
    },
    components: {
        'price': Price,
        'action-name-value-container': ActionNameValueContainer,
        'name-value-container': NameValueContainer,
        'description-container': DescriptionContainer,
        'item-loader': ItemLoader,
    }
};