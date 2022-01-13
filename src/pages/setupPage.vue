<template>
  <div class="setPage">
    <div class="setPage__list" v-for="set in settings" :key="set.name">
      <div class="setPage__listHeader">
        <h3 class="setPage__listTitle">{{set.name}}</h3>
        <img class="setPage__listAdd" src="icons/plus.png" @click='addItem(set.steps)'>
      </div>

      <dragnDropList :listItems="set.steps" :listName='set.tableName' :key="set.steps.length" @listChange='rewriteSetup'/>
    </div>

    <button
      @click = 'saveSetups'
      :class="{'setPage__saveButton_saved': settingsSaved}"
      class="setPage__saveButton headNav__addButton">
      Сохранить
    </button>
  </div>
</template>

<script>
import dragnDropList from '../components/dragnDropList.vue'

export default {
  components: {dragnDropList, },
  
  data: () => ({
    settings: [],
    settingsSaved: false,
  }),
  
  methods: {
    addItem (arr) { arr.push({id: arr.length, text: ''}) },

    rewriteSetup (listName, list) {
      this.settings.forEach(item => {
        if (item.tableName == listName) {
          item.steps = list
          this.settingsSaved = false
        }
      })
    },

    async getSetups () {
      let payersArr = await this.$base.getTable('PAYERS')
      let salestepsArr = await this.$base.getTable('SALESTEPS')
      let servicesArr = await this.$base.getTable('SERVICES')
      let suppliersArr = await this.$base.getTable('SUPPLIERS')

      let salesteps = {name: "Этапы сделки", steps: salestepsArr, tableName: 'SALESTEPS'}
      let suppliers = {name: "Поставщики", steps: suppliersArr, tableName: 'SUPPLIERS'}
      let payers = {name: "Плательщики", steps: payersArr, tableName: 'PAYERS'}
      let services = {name: "За что", steps: servicesArr, tableName: 'SERVICES'}

      this.settings.push(salesteps, suppliers, payers, services)
    },

    async saveSetups () {
      this.settings.forEach(async setting => {
        let cells = []
        let resTable

        setting.steps.forEach(step => {cells.push(step.NAME)})
        resTable = await this.$base.rewriteTable(setting.tableName, cells)

        this.settingsSaved = true
      })
    },
  },

  async created () {
    console.log("getting tables")
    await this.getSetups()
    console.log("getting complete")
  },
}
</script>

<style>
  .setPage {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;

    position: relative;
    background: #ecf0f1;
    padding: 40px;
  }

  .setPage__listHeader {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 20px;
  }

  .setPage__listTitle {margin: 0}
  .setPage__listAdd {
    width: 15px;
    padding: 5px;
  }

  .setPage__saveButton {
    position: absolute;
    bottom: 20px; right: 20px;
  }

  .setPage__saveButton_saved {background: green}
  .setPage__saveButton_saved:hover {background: #2ecc71}
</style>