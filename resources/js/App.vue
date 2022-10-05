<template>
    <notifications/>
    <header class="header">
        <ul class="filter">
            <li class="filter__item">
                <div>Имя</div>
                <div>
                    <input type="text" v-model="filters.name" @input="search" v-on:keyup.enter="onEnter">
                </div>
            </li>
            <li class="filter__item">
                <div>Спальни</div>
                <div>
                    <select v-model.number="filters.bedrooms" @change="search">
                        <option value="0">все</option>
                        <option v-for="item in bedroomOptions" :key="item">{{ item }}</option>
                    </select>
                </div>
            </li>
            <li class="filter__item">
                <div>Ванные комнаты</div>
                <div>
                    <select v-model.number="filters.bathrooms" @change="search">
                        <option value="0">все</option>
                        <option v-for="item in bathroomOptions" :key="item">{{ item }}</option>
                    </select>
                </div>
            </li>
            <li class="filter__item">
                <div>Этажность</div>
                <div>
                    <select v-model.number="filters.storeys" @change="search">
                        <option value="0">все</option>
                        <option v-for="item in storeyOptions" :key="item">{{ item }}</option>
                    </select>
                </div>
            </li>
            <li class="filter__item">
                <div>Гаражи</div>
                <div>
                    <select v-model.number="filters.garages" @change="search">
                        <option value="0">все</option>
                        <option v-for="item in garageOptions" :key="item">{{ item }}</option>
                    </select>
                </div>
            </li>
            <li class="filter__item">
                <div>Цена диапазон ({{ minPrice }} - {{ maxPrice }})</div>
                <div>
                    от
                    <input type="number"
                           v-model.number="filters.min"
                           @change="search"
                           v-on:keyup.enter="onEnter"
                           :min="prises.min"
                           :max="prises.max - 1"
                    >
                </div>
                <div>
                    по
                    <input
                        type="number"
                        v-model.number="filters.max"
                        @change="search"
                        v-on:keyup.enter="onEnter"
                        :min="prises.min + 1"
                        :max="prises.max"
                    >
                </div>
            </li>
            <li class="filter__item">
                <button @click="reset">reset</button>
            </li>
        </ul>
        <form ref="form" action="/api/upload">
            <label for="file" class="upload">upload</label>
            <input id="file" type="file" name="csvFile" ref="upload" @change="sendFile">
        </form>
    </header>
    <main>
        <ul class="list" v-if="apartments.length">
            <li class="list__item">
                <span>id</span>
                <span>Наименование</span>
                <span>Цена</span>
                <span>Спальни</span>
                <span>Ванные</span>
                <span>Этажность</span>
                <span>Гаражи</span>
            </li>
            <li class="list__item" v-for="apartment in apartments" :key="apartment.id">
                <span>{{ apartment.id }}</span>
                <span>{{ apartment.name }}</span>
                <span>{{ apartment.price }}</span>
                <span>{{ apartment.bedrooms }}</span>
                <span>{{ apartment.bathrooms }}</span>
                <span>{{ apartment.storeys }}</span>
                <span>{{ apartment.garages }}</span>
            </li>
        </ul>
    </main>
</template>

<script>
import {notify} from "@kyvg/vue3-notification";

export default {
    name: "App",
    data() {
        return {
            apartments: [],
            prises: {
                min: 0,
                max: 0,
            },
            filters: {
                name: "",
                min: 0,
                max: 0,
                bedrooms: 0,
                bathrooms: 0,
                storeys: 0,
                garages: 0,
            }
        }
    },
    computed: {
        bedroomOptions() {
            return new Set(this.apartments.map(i => i.bedrooms).sort());
        },
        bathroomOptions() {
            return new Set(this.apartments.map(i => i.bathrooms).sort());
        },
        storeyOptions() {
            return new Set(this.apartments.map(i => i.storeys).sort());
        },
        garageOptions() {
            return new Set(this.apartments.map(i => i.garages).sort());
        },
        minPrice() {
            return new Intl.NumberFormat('ru-RU').format(this.prises.min)
        },
        maxPrice() {
            return new Intl.NumberFormat('ru-RU').format(this.prises.max)
        }
    },
    methods: {
        successNote(text, title = "Успешно") {
            notify({
                type: "success",
                title: title,
                text: text,
            });
        },
        warnNote(text, title = "Внимание") {
            notify({
                type: "warn",
                title: title,
                text: text,
            });
        },
        errorNote(text, title = "Ошибка") {
            notify({
                type: "error",
                title: title,
                text: text,
            });
        },
        sendFile() {
            axios.post(this.$refs.form.action, new FormData(this.$refs.form), {
                headers: {"Content-Type": "multipart/form-data"}
            }).then(res => {
                if (res.status === 200) {
                    this.$refs.upload.value = null;
                    this.getAllApartments();
                    this.successNote("файл успешно загружен")
                }
            })
        },
        setData(data) {
            this.apartments = data;
        },
        getAllApartments() {
            axios.get("api/apartments").then(res => {
                if (res.status === 200) {
                    this.setData(res.data)
                    this.getPriceValues();
                    this.successNote("Список успешно получен")
                }
            })
        },
        search() {
            const query = this.getFilters();

            axios.post("api/search", query).then(res => {
                if (res.status === 200) {
                    this.setData(res.data)
                    this.successNote("Список успешно обновлен")
                }
            })
        },
        getFilters() {
            const notEmpty = {}
            for (const val in this.filters) {
                if (this.filters[val]) {
                    notEmpty[val] = this.filters[val];
                }
            }
            return notEmpty;
        },
        getPriceValues() {
            this.prises.min = Math.min(...this.apartments.map(i => i.price));
            this.prises.max = Math.max(...this.apartments.map(i => i.price));

            this.filters.min = this.prises.min;
            this.filters.max = this.prises.max;
        },
        onEnter() {
            this.search();
        },
        reset() {
            this.filters = {
                name: "",
                min: 0,
                max: 0,
                bedrooms: 0,
                bathrooms: 0,
                storeys: 0,
                garages: 0,
            }
            this.getAllApartments();
        },
    },
    mounted() {
        this.getAllApartments();
    }
}
</script>

