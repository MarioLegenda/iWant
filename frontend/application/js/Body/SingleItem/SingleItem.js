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
            showShadow: true,
            nonRevealedStyle: {
                height: '150px',
            },
            revealedStyle: {
                height: 'auto',
            }
        }
    },
    template: `<div class="Row DescriptionWrapper">
                   <div v-if="showShadow" class="ShadowWrapper"></div>
                   <h1 class="DescriptionHeader">Description:</h1>
                   <p class="Description" v-bind:style="style">{{description}}</p>
                                
                   <p v-if="showShadow" @click="showMoreDescription($event)" class="MoreButton">... more</p>
                   <p v-if="!showShadow" @click="showLessDescription($event)" class="MoreButton">... less</p>
               </div>`,
    props: ['description'],
    computed: {
        style: function() {
            return (this.showShadow) ? this.nonRevealedStyle : this.revealedStyle;
        }
    },
    methods: {
        showMoreDescription: function(e) {
            this.showShadow = false;

            console.log(e);
        },

        showLessDescription: function(e) {
            this.showShadow = true;
        }
    }
};


export const SingleItem = {
    data: function() {
        return {
            item: null,
        }
    },
    template: `<div v-if="item" class="SingleItemWrapper">
                    <div class="SingleItem">
                        <div class="CenterPanel">
                            <div class="Seller">
                                <h1>{{item.seller.userId}}</h1>
                                <span>({{item.seller.feedbackScore}})</span>
                                <p>Positive feedback score: <span>{{sellerFeedbackPercent}}%</span></p>
                            </div>
                        </div>
                        
                        <div class="Panel LeftPanel">
                            <div class="Row ThumbnailImageWrapper">
                                <img class="Image" :src="parsePictureUrl(item.pictureUrl)" />
                            </div>
                            
                            <description-container v-bind:description="item.description"></description-container>
                        </div>
                    
                        <div class="Panel RightPanel">
                            <div class="Row TitleWrapper">
                                <h1 class="Title">{{item.title}}</h1>
                            </div>
                            
                            <div class="Row ViewsWrapper LightSeparator">
                                <p>{{item.hitCount}} views</p>
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
                                <button>View ShippingDetails</button>
                            </div>
                            
                            <div class="CenterPanel Border"></div>
                            
                            <name-value-container
                                name="Is auction: "
                                v-bind:value="(item.bidCount !== 0) ? 'Yes' : 'No'">
                            </name-value-container>
                            
                            <name-value-container
                                name="Handling time: "
                                v-bind:value="parseHandlingTimeString(item.handlingTime)">
                            </name-value-container>
                            
                            <name-value-container
                                name="Condition: "
                                v-bind:value="item.conditionDisplayName">
                            </name-value-container>
                            
                            <action-name-value-container 
                                name="Requires immediate payment: "
                                v-bind:value="(item.autoPay === true) ? 'Yes' : 'No'"
                                description="The seller requires immediate payment for the item. Buyers must have a PayPal account to purchase items that require immediate payment">
                            </action-name-value-container>
                            
                            <action-name-value-container
                                name="Best offer feature enabled: "
                                v-bind:value="(item.bestOfferEnabled === true) ? 'Yes' : 'No'"
                                v-bind:description="false">
                                
                                <div slot="description">
                                    <p class="NameValueDescription">This feature indicates whether the seller will accept a Best Offer for the item. The Best Offer feature allows a buyer to make a lower-priced, binding offer on an item. Buyers can't see how many offers have been made (only the seller can see this information)</p>
                                    <p class="NameValueDescription">The Best Offer feature has not been available for auction listings, but beginning with Version 1027, sellers in the US, UK, and DE sites will be able to offer the Best Offer feature in auction listings. The seller can offer Buy It Now or Best Offer in an auction listing, but not both.</p>
                                </div>
                                
                            </action-name-value-container>
                           
                        </div>
                    </div>

               </div>`,
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
    }
};