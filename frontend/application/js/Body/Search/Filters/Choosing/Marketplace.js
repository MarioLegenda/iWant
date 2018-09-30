import {RepositoryFactory} from "../../../../services/repositoryFactory";
import {Modal} from "../../../../global/modal";

const Choice = {
    template: `
                <div>
                    <p v-if="marketplaceSelection()" @click="addMarketplace">{{item.name}}<i v-if="item.selected" class="fas fa-check"></i></p>                
                </div>
    `,
    props: ['item'],
    methods: {
        addMarketplace() {
            this.item.selected = !this.item.selected;

            this.$emit('on-add-marketplace', this.item);
        },
        marketplaceSelection() {
            const supported = ['ebay', 'etsy'];

            for (let item of supported) {
                if (this.item.normalized === item) {
                    return true;
                }
            }

            return false;
        }
    }
};

class SelectedItems {
    constructor() {
        this.items = [];
    }

    length() {
        let len = 0;
        for (let item in this.items) {
            len++;
        }

        return len;
    }

    normalize() {
        let normalized = [];
        for (let item of this.items) {
            if (typeof item !== 'undefined') {
                normalized.push(item);
            }
        }

        return normalized;
    }

    remove(index) {
        this.items.splice(index, 1);
    }

    add(item) {
        this.items[item.index] = item;
    }
}

export const Marketplace = {
    data: function() {
        return {
            showModal: false,
            items: [],
            selectedItems: new SelectedItems()
        }
    },
    template: `<div class="Filter_Filter-filter Filter-select">
                   <p v-on:click="showModal = !showModal">Add marketplace</p>
                   
                   <div class="Modal" id="MarketplaceModal">
                       <modal v-if="showModal" v-on:closeModal="showModal = false">
                           <h1 class="Modal_Header" slot="header">Choose a marketplace</h1>
                           
                           <div class="Modal_Body" slot="body">
                               <choice
                                    v-for="(item, index) in items"
                                    :key="index"
                                    v-bind:item="item"
                                    v-on:on-add-marketplace="onAddMarketplace">
                               </choice>
                           </div>
                           
                           <div class="Modal_Footer" slot="footer">
                               <button class="Modal_ChooseButton" @click="sendItems">Ok</button>
                           </div>
                       </modal>
                   </div>
               </div>`,
    created() {
        if (this.items.length === 0) {
            const marketplaceRepo = RepositoryFactory.create('marketplace');

            marketplaceRepo.getMarketplaces(null, (response) => {
                const responseItems = response.collection.data;
                let componentItems = [];

                let index = 0;
                Object.keys(responseItems).map((v) => {
                    if (responseItems.hasOwnProperty(v)) {
                        componentItems.push({
                            name: responseItems[v],
                            normalized: v,
                            index: index,
                            selected: false
                        });
                    }

                    index++;
                });

                this.items = componentItems;
            });
        }
    },
    methods: {
        sendItems: function() {
            this.showModal = false;

            this.$emit('on-add-marketplaces', this.selectedItems);
        },
        onAddMarketplace: function(item) {
            (item.selected) ? this.selectedItems.add(item) : this.selectedItems.remove(item.index);
        },
    },
    components: {
        'modal': Modal,
        'choice': Choice,
    }
};