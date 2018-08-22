import {events} from "../../../../events";

export const AddedFilter = {
    template: `
               <div>
                   <span @click="removeFilter">
                       {{filter.text}}
                       <i class="fas fa-minus"></i>
                   </span>
               </div>
    `,
    methods: {
        removeFilter() {
            this.$emit(events.FILTER_REMOVE, this.filter.id);
        }
    },
    props: ['filter']
};