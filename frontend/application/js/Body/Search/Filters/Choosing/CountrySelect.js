import {RepositoryFactory} from "../../../../services/repositoryFactory";
import {Modal} from "../../../../global/modal";

export const Choice = {
    data: function() {
        return {
            markChecked: false
        }
    },
    template: `
                <div>
                    <p @click="addCountry">{{item.name}}<i v-if="markChecked" class="fas fa-check"></i></p>                
                </div>
    `,
    props: ['item', 'markUnchecked'],
    methods: {
        addCountry() {
            this.markChecked = !this.markChecked;

            this.$emit('add-country', this.item.id);
        }
    }
};

export const CountrySelect = {
    data: function() {
        return {
            removeChoices: false,
            isWorldwide: false,
            showModal: false,
            items: [],
            currentlyChosen: [],
            totalChosen: [],
            computedMarked: false
        }
    },
    template: `<div class="Filter_Filter-filter Filter-select">
                   <p v-on:click="showModal = !showModal">{{data.text}}</p>
                   
                   <div class="Modal" id="ShippingCountryModal">
                       <modal v-if="showModal" v-on:closeModal="showModal = false">
                           <h1 class="Modal_Header" slot="header">Choose a shipping country</h1>
                           
                           <div class="Modal_Body" slot="body">
                               <p @click="onAddCountry('worldwide')">Worldwide<i v-if="isWorldwide" class="fas fa-check"></i></p>
                               <choice 
                                    v-for="(item, index) in items"
                                    :key="index" 
                                    v-bind:item="item"
                                    v-bind:markUnchecked="removeChoices"
                                    v-on:add-country="onAddCountry">
                               </choice>
                           </div>
                           
                           <div class="Modal_Footer" slot="footer">
                               <button class="Modal_ChooseButton" @click="onClick">Ok</button>
                           </div>
                       </modal>
                   </div>
               </div>`,
    props: ['data'],
    created() {
        if (this.items.length === 0) {
            const countryRepo = RepositoryFactory.create('country');

            countryRepo.getCountries(null, (response) => {
                this.items = response.collection.data;
            });
        }
    },
    methods: {
        onAddCountry(id) {
            if (id === 'worldwide') {
                if (this.totalChosen.includes('worldwide')) {
                    this.isWorldwide = false;

                    this.currentlyChosen = [];
                    this.totalChosen = [];

                    return false;
                }

                this.removeChoices = true;

                this.currentlyChosen = [];
                this.totalChosen = [];

                this.currentlyChosen.push(id);
                this.totalChosen.push(id);

                this.isWorldwide = true;

                return false;
            }

            if (!this.currentlyChosen.includes(id) && !this.totalChosen.includes(id)) {
                this.currentlyChosen.push(id);
            }

            if (!this.totalChosen.includes(id)) {
                this.totalChosen.push(id);
            }
        },
        onClick() {
            let resolvedCountries = [];

            if (this.currentlyChosen.length === 0) {
                resolvedCountries = null;

                this.$emit('on-country-select', {
                    countries: resolvedCountries,
                    filter: this.data
                });

                this.currentlyChosen = [];

                this.showModal = false;

                return false;
            }

            for (let id of this.currentlyChosen) {
                if (id === 'worldwide') {
                    resolvedCountries = 'worldwide';

                    break;
                }

                resolvedCountries.push(this.findItemById(id));
            }

            this.currentlyChosen = [];

            this.$emit('on-country-select', {
                countries: resolvedCountries,
                filter: this.data
            });

            this.showModal = false;
        },

        findItemById(id) {
            for (let item of this.items) {
                if (item.id === id) {
                    return item;
                }
            }

            return false;
        }
    },
    components: {
        'modal': Modal,
        'choice': Choice,
    }
};