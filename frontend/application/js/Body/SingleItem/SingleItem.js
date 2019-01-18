import {Price} from "../../services/util";
import {ActionNameValueContainer} from "./ActionNameValueContainer";
import {NameValueContainer} from "./NameValueContainer";
import {DescriptionContainer} from "./DescriptionContainer";
import {ItemLoader} from "./ItemLoader";
import {ShippingDetails} from "./ShippingDetails";


export const SingleItem = {
    data: function() {
        return {
            item: null,
            toggleShowShippingDetails: false
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
                                    v-bind:currency="item.convertedCurrentPrice.currency"
                                    v-bind:price="item.convertedCurrentPrice.price" >
                                </price>
                                
                                <price
                                    v-bind:currency="item.currentPrice.currency"
                                    v-bind:price="item.currentPrice.price" >
                                </price>
                            </div>
                            
                            <div class="Row ShippingOptionsWrapper">
                                <button @click="showShippingDetails">{{translationsMap.productPage.viewShippingDetails}}<i class="fas fa-truck"></i></button>
                            </div>
                            
                            <div class="CenterPanel Border"></div>
                            
                            <div class="NameValueContainerWrapper">
                                <name-value-container
                                    v-bind:name="translationsMap.productPage.endsOn"
                                    v-bind:value="item.endTime | userFriendlyDate">
                                </name-value-container>
                            </div>
                            
                            <div class="NameValueContainerWrapper">
                                <name-value-container
                                    v-bind:name="translationsMap.productPage.isAuction"
                                    v-bind:value="item.isAuction ? translationsMap.yes : translationsMap.no">
                                </name-value-container>
                            </div>
                            
                            <div class="NameValueContainerWrapper">
                                <name-value-container
                                    v-bind:name="translationsMap.productPage.handlingTime"
                                    v-bind:value="decideHandlingTimeDesc(item.handlingTime) | replace(item.handlingTime)">
                                </name-value-container>
                            </div>
                            
                            <div class="NameValueContainerWrapper">
                                <name-value-container
                                    v-bind:name="translationsMap.productPage.condition"
                                    v-bind:value="item.condition.conditionDisplayName">
                                </name-value-container>
                            </div>
                            
                            <div class="NameValueContainerWrapper">
                                <action-name-value-container 
                                    v-bind:name="translationsMap.productPage.requiresImmediatePayment"
                                    v-bind:value="(item.autoPay === true) ? translationsMap.yes : translationsMap.no"
                                    :description="false">
                                    
                                    <div slot="description">
                                        <p class="NameValueDescription">{{translationsMap.productPage.requiresImmediatePaymentExplanation}}</p>
                                    </div>
                                    
                                </action-name-value-container>
                            </div>
                            
                            <div class="NameValueContainerWrapper">
                                <action-name-value-container
                                    v-bind:name="translationsMap.productPage.bestOfferFeatureEnabled"
                                    v-bind:value="(item.bestOfferEnabled === true) ? translationsMap.yes : translationsMap.no"
                                    v-bind:description="false">
                                
                                    <div slot="description">
                                        <p class="NameValueDescription">{{translationsMap.productPage.bestOfferFeatureExplanation_1}}</p>
                                        <p class="NameValueDescription">{{translationsMap.productPage.bestOfferFeatureExplanation_2}}</p>
                                    </div>
                                
                                </action-name-value-container>
                            </div>
                            
                            <div class="Row ViewOnEbayButtonWrapper">
                                <a :href="item.viewItemUrl" target="_blank">{{translationsMap.productPage.viewOnEbay}}<i class="fas fa fa-link"></i></a>
                            </div>
                           
                        </div>
                        
                        <div class="Panel LeftPanel">
                            <div class="Row Seller">
                                <h1>{{item.seller.sellerId}}</h1>
                                <span>({{item.seller.feedbackScore}})</span>
                                <p>{{translationsMap.productPage.positiveFeedbackScore}}<span>{{sellerFeedbackPercent}}%</span></p>
                            </div>
                            
                            <div class="Row ThumbnailImageWrapper">
                                <img class="Image" :src="parsePictureUrl(item.pictureUrl)" />
                            </div>
                            
                            <description-container v-bind:description="item.description"></description-container>
                        </div>

                    </div>
                    
                        <shipping-details
                            v-if="item && toggleShowShippingDetails"
                            v-on:before-modal-close="onShippingDetailsClose"
                            :item-id="item.itemId"
                            :ships-to-locations="item.shipsToLocations"
                            :exclude-ship-to-locations="item.excludeShipToLocations">
                        </shipping-details>

               </div>
           </transition>`,
    created() {
        if (this.item === null) {
            const paths = window.location.pathname.split('/');

            const itemId = paths[4];

            this.$repository.SingleItemRepository.getSingleItem({
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
        showShippingDetails() {
            this.toggleShowShippingDetails = true;

            setTimeout(() => {
                this.$modal.show('shipping-details-modal');
            }, 500);
        },

        onShippingDetailsClose() {
            this.toggleShowShippingDetails = false;
        },

        parsePictureUrl(pictureUrl) {
            if (pictureUrl !== null && typeof pictureUrl !== 'undefined') {
                return pictureUrl[0];
            }
        },

        decideHandlingTimeDesc(handlingTime) {
            if (isNumber(handlingTime)) {
                if (handlingTime !== 0) {
                    return this.translationsMap.productPage.handlingTimeDescription
                }

                if (handlingTime === 0) {
                    return this.translationsMap.productPage.handlingTimeImmediately;
                }
            }

            return this.translationsMap.productPage.handlingTimeUndefined;
        }
    },
    filters: {
        replace: function(messageString, ...replacements) {
            for (const num in replacements) {
                const rgx = new RegExp(`\\(${num}\\)`);
                const replacement = replacements[num];

                messageString = messageString.replace(rgx, replacement);
            }

            return messageString;
        }
    },
    components: {
        'price': Price,
        'action-name-value-container': ActionNameValueContainer,
        'name-value-container': NameValueContainer,
        'description-container': DescriptionContainer,
        'item-loader': ItemLoader,
        'shipping-details': ShippingDetails,
    }
};