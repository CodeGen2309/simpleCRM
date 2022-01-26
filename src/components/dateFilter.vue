<template>
  <div class="filter">
    <label class="filter__label">
      <span class="filter__text">год</span>
      <select class="filter__select filter__select_year"
      @change="changeYear($event.target.value)">
        <option v-for="(year, yearIndex) in years"
        :selected="year == 2021" :key='yearIndex' :value="year">
          {{year}}
        </option>
      </select>
    </label>

    <label class="filter__label">
      <span class="filter__text">месяц</span>
      <select class="filter__select filter__select_year"
      @change="changeMonth($event.target.value)">
        <option v-for="(month, monthIndex) in months"
        :selected="monthIndex == 0" :key='monthIndex' :value="monthIndex">
          {{month}}
        </option>
      </select>
    </label>
  </div>
</template>

<script>
export default {
  data: () => ({
    emits: ['dateIsChanged', ],
    years: ['2020', '2021', '2022'],

    months: [
      'январь', 'февраль', 'март', 'апрель',
      'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь',
      'ноябрь', 'декабрь'
    ],

    currMonth: 3,
    currYear: 2022,
  }),

  methods: {
    changeYear (value) {
      this.currYear = value
      this.dateChanged()
    },

    changeMonth (arrIndex) {
      this.currMonth = Number(arrIndex) + 1
      this.dateChanged()
    },

    dateChanged () {
      let currDate = [String(this.currYear), String(this.currMonth)]
      this.$emit('dateIsChanged', currDate)
    },
  },
}
</script>

<style>
.filter {
  display: flex;
  gap: 20px;
}

.filter__label {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter__text {}
.filter__select {
  padding: 5px 10px;
  outline: none;
}
.filter__select_year {}
</style>