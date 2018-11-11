import {RepositoryFactory} from "../../services/repositoryFactory";

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
                                <p>Feedback score: <span>{{sellerFeedbackPercent}}%</span></p>
                            </div>
                        </div>
                        
                        <div class="Panel LeftPanel">
                            <div class="Row ThumbnailImageWrapper">
                                <img class="Image" :src="parsePictureUrl(item.pictureUrl)" />
                            </div>
                        </div>
                    
                        <div class="Panel RightPanel">
                            <div class="Row TitleWrapper">
                                <h1 class="Title">{{item.title}}</h1>
                            </div>
                            
                            <div class="Row ViewsWrapper LightSeparator">
                                <p>{{item.hitCount}} views</p>
                            </div>
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
                console.log(r.resource.data);
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
        }
    }
};