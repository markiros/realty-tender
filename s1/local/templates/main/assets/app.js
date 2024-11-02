import {createApp} from './vue.esm-browser.js'

createApp({
    data() {
        return {
            filter: {
                house: '',
                hasDiscount: false,
            },
            filterData: {},
            apartments: [],
            apartment: {},
        }
    },
    created() {
        this.fetchFilter()
        this.selectApartments()
    },
    methods: {
        async fetchFilter() {
            const res = await fetch('/api/filter')
            const { data } = await res.json()
            this.filterData = data
        },

        async selectApartments() {
            const params = new URLSearchParams({
                house: this.filter.house,
                hasDiscount: this.filter.hasDiscount
            }).toString();
            const res = await fetch(`/api/find-apartments?${params}`)
            const { data } = await res.json()
            this.apartments = data
        },

        async fetchApartment(id) {
            const res = await fetch(`/api/apartments/${id}`)
            const { data } = await res.json()
            this.apartment = data
        },

        submitFilter() {
            this.selectApartments()
        },

        // Apartments
        showApartment(id) {
            this.fetchApartment(id)
            const myModal = new bootstrap.Modal(this.$refs.apartmentModal)
            myModal.show()
        },

    }
}).mount('#app')
