<template>
  <div class="dragList">
    <draggable class="dragList__list" v-model="listItems" @drop="onListChange">
      <template v-slot:item='{item}'>
        <div class="dragList__item">
          <img class="dragList__dragIcon" src="/icons/dragIcon.svg">
          <input class="dragList__input" type="text" v-model="item.NAME" @change="onListChange">
          <img class="dragList__delIcon" src="icons/delIcon.svg" alt="" @click="delItem(item)">
        </div>
      </template>
    </draggable>
  </div>
</template>

<script>
import draggable from 'vue3-draggable'

export default {
  props: ['listItems', 'listName'],
  components: {draggable, },
  emits: ['listChange', ],

  methods: {
    delItem (toDel) {
      let itemIndex = this.listItems.indexOf(toDel)
      let deleted = this.listItems.splice(itemIndex, 1)

      this.onListChange()
    },

    onListChange () {
      setTimeout(() => {
        this.$emit('listChange', this.listName, this.listItems)
      }, 100);
    },
  },
}
</script>

<style>
  .dragList {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .dragList__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .dragList__title {
    font-size: 16px;
    margin: 0;
  }

  .dragList__addItem {
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    transition: .3s;
  }

  .dragList__addItem:hover {
    background: whitesmoke;
  }

  .dragList__list {
    display: flex;
    flex-direction: column;
    gap: 10px;

    margin: 0; padding: 0;
    list-style: none;
  }

  .dragList__item {
    display: flex;
    align-items: center;

    border-radius: 2px;
    background: white;
    padding-right: 10px;
  }

  .dragList__item:hover .dragList__delIcon {
    opacity: 1;
  }

  .dragList__dragIcon {
    height: 10px;
    padding: 15px 10px;
    cursor: grab;
  }

  .dragList__input {
    flex-grow: 1;
    padding: 2px;
    border: none; outline: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0);
    background: none;

    transition: .3s;
  }

  .dragList__input:focus {
    border-bottom: 1px solid rgba(0, 0, 0, .6);
  }

  .dragList__delIcon {
    width: 15px;
    margin: 0 5px;
    opacity: 0;

    cursor: pointer;
    transition: .3s;
  }
</style>