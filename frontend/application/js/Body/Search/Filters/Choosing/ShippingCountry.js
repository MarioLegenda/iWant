import {RepositoryFactory} from "../../../../services/repositoryFactory";
import {Modal} from "../../../../global/modal";

const Choice = {
    template: `
                <div>
                    <p @click="addCountry">{{item.name}}<i v-if="item.selected" class="fas fa-check"></i></p>                
                </div>
    `,
    props: ['item'],
    methods: {
        addCountry() {
            this.item.selected = !this.item.selected;

            this.$emit('on-add-country', this.item);
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

export const ShippingCountry = {
    data: function() {
        return {
            showModal: false,
            items: [],
            selectedItems: new SelectedItems()
        }
    },
    template: `<div class="Filter_Filter-filter Filter-select">
                   <p v-on:click="showModal = !showModal">Add shipping countries</p>
                   
                   <div class="Modal" id="ShippingCountryModal">
                       <modal v-if="showModal" v-on:closeModal="showModal = false">
                           <h1 class="Modal_Header" slot="header">Choose a shipping country</h1>
                           
                           <div class="Modal_Body" slot="body">
                               <choice
                                    v-for="(item, index) in items"
                                    :key="index"
                                    v-bind:item="item"
                                    v-on:on-add-country="onAddCountry">
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
            const countryRepo = RepositoryFactory.create('country');

            countryRepo.getCountries(null, (response) => {
                const responseItems = response.collection.data;
                let componentItems = [];

                for (let i = 0; i < responseItems.length; i++) {
                    const responseItem = responseItems[i];

                    responseItem.selected = false;
                    responseItem.index = i;

                    componentItems.push(responseItem);
                }

                this.items = componentItems;
            });
        }
    },
    methods: {
        sendItems: function() {
            this.showModal = false;

            this.$emit('on-add-shipping-countries', this.selectedItems);
        },
        onAddCountry: function(item) {
            (item.selected) ? this.selectedItems.add(item) : this.selectedItems.remove(item.index);
        },
    },
    components: {
        'modal': Modal,
        'choice': Choice,
    }
};