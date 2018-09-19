import {RepositoryFactory} from "../../services/repositoryFactory";

export const SingleItem = {
    data: function() {
        return {
            item: {},
        }
    },
    template: `<div>
                    <p>{{item.title}}</p>
                    <p>{{item.description}}</p>
               </div>`,
    created() {
        if (this.singleItem !== null) {
            const singleItemRepo = RepositoryFactory.create('single-item');

            singleItemRepo.getSingleItem({
                marketplace: this.singleItem.marketplace,
                itemId: this.singleItem.itemId,
            }, (response) => {
                this.item = response.resource.data;
            });
        } else if (this.singleItem === null) {
            const singleItemRepo = RepositoryFactory.create('single-item');

            const paths = window.location.pathname.split('/');

            const marketplace = paths[2];
            const itemId = paths[4];

            singleItemRepo.getSingleItem({
                marketplace: marketplace,
                itemId: itemId,
            }, (response) => {
                this.item = response.resource.data;
            });
        }
    },
    computed: {
        singleItem: function() {
            return this.$store.state.singleItem;
        }
    }
};