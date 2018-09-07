<template>
  <div class="cardsDiv">
    <div style="width:100%;font-size: 36px;text-align:center;margin-bottom:25px;">PIA cards are here!</div>
    <div class="cardDivInner">
      <img src="../assets/cards.png" class="pia_cards"/>
    </div>
    <div class="cardsBlurb">
      PIA has launced a program to help businesses earn money, and spread privacy at the same time!<br><br>
      How it works: We are carpet bombing shops around the US with free 3 day passes. Business owners stand to earn money from subscriptions that result from the use of these passes, and new users get the 3 days free as a freebie.<br><br>
      It's a win-win!<br><br>
      This site will let you find the nearest businesses that are already participating, or if you are a business owner, you can register your business here so people can find you!
    </div>
    <div class="clear"></div>
    <hr>
    <i style="font-size: .75em">Click a thumbnail to view gallery<br></i>
    <div class="thumbContainer">
      <photo-gallery :images="images" v-model="visiblePhoto"></photo-gallery>
      <a v-for="(image, index) in images" href="#" @click="visiblePhoto = index" :key='index'>
        <img :src="image.thumbSrc" class="thumb-item"/>
      </a>
    </div>
    <div class="clear"></div>
  </div>
</template>

<script>
import { PhotoGallery } from 'vue-photo-gallery'

export default {
  name: 'Cards',
  components: {
    PhotoGallery
  },
  data () {
    return {
      visiblePhoto: null,
      images: []
    }
  },
  mounted () {
    let sizes = [
      [ 800, 1422 ],
      [ 800, 1422 ],
      [ 800, 1231 ],
      [ 1000, 545 ],
      [ 800, 984 ],
      [ 800, 1067 ],
      [ 800, 537 ],
      [ 800, 600 ],
      [ 800, 1067 ],
      [ 800, 1067 ]
    ]
    for (let i = 1; i <= 10; ++i) {
      this.images.push(
        {
          src: require(`../assets/cardbox${i}.jpg`),
          thumbSrc: require(`../assets/cardbox${i}_thumb.jpg`),
          w: sizes[i - 1][0],
          h: sizes[i - 1][1]
        }
      )
    }
  }
}
</script>

<style scoped>
.pia_cards {
  width: 100%;
  border-radius: 5px;
}
.cardDivInner {
  text-align: center;
  float: left;
  width: calc(300px - 22px);
  margin:10px;
}
.cardsBlurb {
  text-align: justify;
  float: left;
  width: calc(100% - 350px);
  margin:10px;
}
.cardsDiv {
  font-size: 20px;
  margin-top: -30px;
}
.thumb-item {
  margin: 8px;
  border-radius: 5px;
}
.thumbContainer {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}
.clear {
  clear: both;
}
@media (max-width: 800px) {
  .cardsBlurb {
    width: 100%;
  }
  .cardDivInner {
    float:none;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    margin:10px;
  }
}
</style>
