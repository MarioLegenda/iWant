import {events} from "../../../../events";

export const ButtonFilter = {
    data: function() {
        return {
            added: false
        }
    },
    template: `
            <button @click="addFilter" v-bind:class="toggleWorkingClass">
                {{filterData.text}}
                <i class="fas fa-plus"></i>
            </button>
    `,
    props: ['filterData'],
    computed: {
        toggleWorkingClass: function() {
            return {
                'disable-added-filter': this.filterData.active === false,
                'filter': this.filterData.active === true,
            }
        }
    },
    methods: {
        addFilter: function() {
            this.$emit(events.FILTER_ADDED, this.filterData.id);
        }
    }
};