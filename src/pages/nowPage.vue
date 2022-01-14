<template>
  <div class="nowPage">
    <div class="nowPage__step step" 
      v-for="(step, stepIndex) in salesteps" :key='stepIndex'>

      <div class="step__header">
        <p class="step__title">{{step}}</p>
        <p class="step__note">25 сделок: 500 000 ₽</p>
      </div>

      <draggable v-model="sells" class="step__sells">
        <template v-slot:item='{ item }'>
          <div class="step__sellItem">
            <p class="step__sellTitle">{{item.name}}</p>
            <p class="step__sellId">№ {{item.id}}</p>
            <span class="step__sellPrice">{{item.price}} ₽</span>
            <p class="step__sellNote">{{item.comments}}</p>
          </div>
        </template>
      </draggable>
    </div>
  </div>
</template>

<script>
import draggable from 'vue3-draggable'

export default {
  components: {draggable, },
  data: () => ({
    salesteps: [],
    sells: [
      {name: "Стол", id: '1838', price: '9220', comments: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, a!'},
      {name: "Стол", id: '1838', price: '9220', comments: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, a!'},
    ],
  }),

  async created () {
    let res = await this.$base.getTable('SALESTEPS')
    res.forEach(item => {this.salesteps.push(item.NAME)})
  }
}
</script>

<style>
  .nowPage {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;

    padding: 20px;
    background: #ecf0f1;
  }

  .nowPage__step {max-width: 250px}
  .step {
    display: flex;
    flex-direction: column;
  }

  .step__header {
    padding: 20px 40px 20px 10px;
    margin-bottom: 10px;
    border-radius: 5px;

    background: #FFDD85;
  }
  
  .step__title {
    margin: 0; padding: 0;
    font-weight: 700;
  }

  .step__note {
    margin: 0; padding: 0;
  }

  .step__sells {
    display: flex;
    flex-direction: column;
    gap: 20px;

    min-height: 100px;
  }

  .step__sellItem {
    display: flex;
    flex-direction: column;
    align-items: flex-start;

    padding: 10px;
    border-radius: 5px;
    background: white;
    box-shadow: 3px 3px 3px rgba(0, 0, 0, .3);
  }

  .step__sellTitle {
    margin: 0; padding: 0;
    font-weight: 700;
  }

  .step__sellId {
    margin: 10px 0; padding: 0;
    font-weight: 300;
    font-size: 12px;
  }

  .step__sellPrice {
    background: gainsboro;
    padding: 5px 10px;
    border-radius: 10px;
  }
  
  .step__sellNote {}
</style>