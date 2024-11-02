import {createApp} from './vue.esm-browser.js'

createApp({
    data() {
        return {
            houses: [],
            house: {},
            apartments: [],
            apartment: {},
        }
    },
    created() {
        this.fetchApartments()
        this.fetchHouses()
    },
    methods: {
        async fetchApartments() {
            const res = await fetch(`/api/apartments`)
            const { data } = await res.json()
            this.apartments = data
        },

        async fetchApartment(id) {
            const res = await fetch(`/api/apartments/${id}`)
            const { data } = await res.json()
            this.apartment = data
        },

        async fetchHouses() {
            const res = await fetch(`/api/houses`)
            const { data } = await res.json()
            this.houses = data
        },

        clearHouse() {
            this.house = {
                id: '',
                address: '',
                photos: [],
            }
        },

        clearApartment() {
            this.apartment = {
                id: '',
                active: false,
                status: false,
                number: '',
                price: '',
                price_discount: '',
                house_id: '',
                address: '',
                photos: [],
                house_photos: [],
            }
        },

        removeApartmentPhoto(index) {
            this.apartment.photos.splice(index, 1)
        },

        removeHousePhoto(index) {
            this.house.photos.splice(index, 1)
        },

        // Apartments
        showApartment(id) {
            this.fetchApartment(id)
            const myModal = new bootstrap.Modal(this.$refs.apartmentModal)
            myModal.show()
        },

        // Открывает модалку с формой создания квартиры
        createApartment() {
            this.clearApartment()
            this.fetchHouses()
            const createApartmentModal = new bootstrap.Modal(this.$refs.createApartmentModal)
            createApartmentModal.show()
        },

        editApartment(id) {
            this.fetchApartment(id)
            this.fetchHouses()
            const editApartmentModal = new bootstrap.Modal(this.$refs.editApartmentModal)
            editApartmentModal.show()
        },

        async deleteApartment(id) {
            await fetch(`/api/apartments/delete/${id}`, {
                method: 'POST'
            })
            this.fetchApartments()
        },

        async submitCreateApartment() {
            const formData = new FormData;
            formData.append('active', this.apartment.active)
            formData.append('status', this.apartment.status)
            formData.append('number', this.apartment.number)
            formData.append('price', this.apartment.price)
            formData.append('price_discount', this.apartment.price_discount)
            formData.append('house_id', this.apartment.house_id)

            const files = this.$refs.inputPhotosCreate.files;
            for (let i = 0; i < files.length; i++) {
                formData.append('photos[]', files[i])
            }

            const result = await fetch('/api/apartments', {
                method: 'POST',
                body: formData
            })
            const data = await result.json()
            if (data.status === 'error') {
                alert('Ошибка создания квартиры')
            }

            this.$refs.inputPhotosCreate.value = null

            this.fetchApartments()
        },

        async submitUpdateApartment() {
            const formData = new FormData;
            formData.append('active', this.apartment.active)
            formData.append('status', this.apartment.status)
            formData.append('number', this.apartment.number)
            formData.append('price', this.apartment.price)
            formData.append('price_discount', this.apartment.price_discount)
            formData.append('house_id', this.apartment.house_id)

            const ids = this.apartment.photos.map(photo => photo.id);
            formData.append('photos_ids', ids)

            const files = this.$refs.inputPhotosEdit.files;
            for (let i = 0; i < files.length; i++) {
                formData.append('photos[]', files[i])
            }

            const result = await fetch(`/api/apartments/update/${this.apartment.id}`, {
                method: 'POST',
                body: formData
            })
            const data = await result.json()
            if (data.status === 'error') {
                alert('Ошибка обновления квартиры')
            }

            this.$refs.inputPhotosEdit.value = null

            this.fetchApartments()
        },

        // Houses
        createHouse() {
            this.clearHouse()
            const createHouseModal = new bootstrap.Modal(this.$refs.createHouseModal)
            createHouseModal.show()
        },

        editHouse(id) {
            this.fetchHouse(id)
            const editHouseModal = new bootstrap.Modal(this.$refs.editHouseModal)
            editHouseModal.show()
        },

        async fetchHouse(id) {
            const res = await fetch(`/api/houses/${id}`)
            const { data } = await res.json()
            this.house = data
        },

        async deleteHouse(id) {
            await fetch(`/api/houses/delete/${id}`, {
                method: 'POST'
            })
            this.fetchHouses()
        },

        async submitCreateHouse() {
            console.log(this.$refs.inputHousePhotosCreate.files)

            const formData = new FormData;
            formData.append('address', this.house.address)

            const files = this.$refs.inputHousePhotosCreate.files;
            for (let i = 0; i < files.length; i++) {
                formData.append('photos[]', files[i])
            }

            const result = await fetch('/api/houses', {
                method: 'POST',
                body: formData
            })
            const data = await result.json()
            if (data.status === 'error') {
                alert('Ошибка создания дома')
            }

            this.$refs.inputHousePhotosCreate.value = null

            this.fetchHouses()
        },

        async submitUpdateHouse() {
            const formData = new FormData;
            formData.append('address', this.house.address)

            const ids = this.house.photos.map(photo => photo.id);
            formData.append('photos_ids', ids)

            const files = this.$refs.inputPhotosEdit.files;
            for (let i = 0; i < files.length; i++) {
                formData.append('photos[]', files[i])
            }

            const result = await fetch(`/api/houses/update/${this.house.id}`, {
                method: 'POST',
                body: formData
            })
            const data = await result.json()
            if (data.status === 'error') {
                alert('Ошибка обновления дома')
            }

            this.$refs.inputPhotosEdit.value = null

            this.fetchHouses()
        },
    }
}).mount('#app')
