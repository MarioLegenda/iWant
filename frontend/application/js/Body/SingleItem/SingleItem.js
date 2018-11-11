import {RepositoryFactory} from "../../services/repositoryFactory";

export const SingleItem = {
    data: function() {
        return {
            item: null,
        }
    },
    template: `<div v-if="item" class="SingleItemWrapper">
                    <div class="Panel LeftPanel">
                        <div class="Row ThumbnailImageWrapper">
                            <img :src="item.galleryUrl" />
                        </div>
                    </div>
                    
                    <div class="Panel RightPanel">
                        <div class="Row TitleWrapper">
                            <h1 class="Title">{{item.title}}</h1>
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
};