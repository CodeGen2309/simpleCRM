<template>
<div class="cardList">
  <div class="cardList__header">
    <p class="cardList__title">{{listName}}</p>
    <p class="cardList__note">{{count}} сделок: {{total}} ₽</p>
  </div>
  
  <draggable v-model="listData" class="cardList__sells"
    @drop="initUpdate"
  >
    <template v-slot:item='{ item }'>
      <div class="cardList__sellItem" @click="$emit('clickOnSale', item)">
        <p class="cardList__sellTitle">{{item.NAME}}</p>
        <p class="cardList__sellId">№ {{item.ID}}</p>
        <span class="cardList__sellPrice">{{$formatter.withSpaces(item.PRICE)}} ₽</span>
        <p class="cardList__sellNote">{{item.COMMENT}}</p>
      </div>
    </template>
  </draggable>
</div>
</template>

<script>
import draggable from 'vue3-draggable'

export default {
  props: ['listData', 'listName', 'listIndex', ],
  emits: ['clickOnSale', 'listChanged'],
  components: {draggable, },
  data: () => ({}),

  methods: {
    updateList () {
      for (let item of this.listData) {
        item.STATUS_ID = this.listIndex + 1
      }

      setTimeout(() => {
        this.$emit('listChanged', this.listData)
      }, 100);
    },

    initUpdate () {
      setTimeout(() => this.updateList(), 100);
    },    
  },

  computed: {
    total () {
      let total = 0

      for (let sale of this.listData) {total += Number(sale.PRICE)}
      return this.$formatter.withSpaces(total)
    },

    count () {
      let count = 0
      for (let sale of this.listData) {count++}
      return count
    }
  },

  created () {
    console.log(this.listData)
  },
}
</script>

<style>
.cardList {
  display: flex;
  flex-direction: column;
}

.cardList__header {
  padding: 20px 40px 20px 10px;
  margin-bottom: 10px;
  border-radius: 5px;

  background: #FFDD85;
}

.cardList__title {
  margin: 0; padding: 0;
  font-weight: 700;
}

.cardList__note {
  margin: 0; padding: 0;
}

.cardList__sells {
  display: flex;
  flex-direction: column;
  gap: 20px;

  min-height: 100px;
}

.cardList__sellItem {
  display: flex;
  flex-direction: column;
  align-items: flex-start;

  padding: 10px;
  border-radius: 5px;
  background: white;
  box-shadow: 3px 3px 3px rgba(0, 0, 0, .3);
}

.cardList__sellTitle {
  margin: 0; padding: 0;
  font-weight: 700;
}

.cardList__sellId {
  margin: 10px 0; padding: 0;
  font-weight: 300;
  font-size: 12px;
}

.cardList__sellPrice {
  background: gainsboro;
  padding: 5px 10px;
  border-radius: 10px;
}

.cardList__sellNote {}
</style>