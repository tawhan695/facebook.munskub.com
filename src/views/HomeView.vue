<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import Countdown from '@/components/Countdown.vue'
import FormRegister from '@/components/FormRegister.vue'

const dataRegis = ref<string[] | null>(null)
const regisLength = computed(() => dataRegis.value ? dataRegis.value.length + 5290 : 0)

const scrollToSection = (sectionId:any) => {
  const section = document.getElementById(sectionId)
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' })
  }
}

const getRegis = async () => {  
  const getData = await fetch(import.meta.env.VITE_SHEET_URL)
  const data = await getData.json()
  dataRegis.value = data
}

onMounted(() => {
  getRegis()
  scrollToSection('form-register')
})
</script>

<template>
  <main class="max-w-5xl mx-auto bg-[#0E220A] p-[10px] w-full">
    <div class="flex flex-col justify-center items-center py-3 ">
      <div class="">
        <video src="@/assets/edit2/IMG_9877.MP4" type="video/mp4"  autoplay controls ></video>
      </div>
    </div>
    <div class="relative pb-20">
      <div class="cursor-pointer" @click="scrollToSection('form-register')">
        <img
          src="@/assets/edit2/photo_2567-09-25 17.05.04.jpeg"
          class="w-full h-full rounded-xl "
        />
      </div>
      <div class="cursor-pointer mt-3" @click="scrollToSection('form-register')">
        <img
          src="@/assets/edit2/photo_2567-09-25 18.36.41.jpeg"
          class="w-full h-full rounded-xl "
        />
      </div>

      <div class="pt-3">
        <div class="bg-white h-7 md:h-8 rounded-full relative">
          <div
            class="absolute w-1/2 rounded-full bg-gradient-to-r from-yellow-300 to-[#FFD700] h-full top-0 left-0"
          />
          <div class="flex items-center relative md:gap-x-2 z-10 h-full px-1.5 md:px-3">
            <img src="@/assets/fire.png" class="w-7 h-7 md:w-8 md:h-8" alt="fire" />
            <div class="text-yellow-900 text-base md:text-lg">สมัครสมาชิกไปแล้ว {{regisLength.toLocaleString()}} User</div>
          </div>
        </div>
      </div>
      <div class="mt-4 py-3">
        <a
            href=" https://lin.ee/DTGfzDr"
            class="btn-line flex items-center text-black justify-center rounded-md h-10 md:h-12 text-sm md:text-base"
          >
            <img src="@/assets/icon_line01.png" class="w-6 h-6 mr-1" alt="telegram" />
            สมัครผ่านไลน์
          </a>
      </div>
      <!-- <div class="flex flex-col justify-center items-center py-3">
        <p class="text-white text-center text-lg pb-2">โปรโมชั่นกำลังจะสิ้นสุด</p>
        <Countdown />
      </div> -->

      <!-- <div id="form-register" class="mt-4">
        <FormRegister :dataRegis="dataRegis" :fetchRegis="getRegis" />
      </div> -->

      <div class="fixed bottom-0 left-1/2 -translate-x-1/2 py-3 px-3 max-w-5xl w-full bg-[#212121]">
        <div class="grid grid-cols-2 w-full gap-x-3">
          <a 
            href=" https://lin.ee/DTGfzDr"
            class="btn-register flex items-center text-black justify-center rounded-md h-10 md:h-12 text-sm md:text-base"
          >
            <img src="@/assets/icon_member01.png" class="w-6 h-6 mr-1" alt="telegram" />
            สมัครผ่านเว็บ
          </a>

          <a
            href=" https://lin.ee/DTGfzDr"
            class="btn-line flex items-center text-black justify-center rounded-md h-10 md:h-12 text-sm md:text-base"
          >
            <img src="@/assets/icon_line01.png" class="w-6 h-6 mr-1" alt="telegram" />
            สมัครผ่านไลน์
          </a>

          <!-- <a
            href="https://t.me/lifeline_official"
            class="btn-line  flex items-center justify-center rounded-md h-12  text-xs sm:text-sm font-semibold"
          >
            <img src="@/assets/icon_telegram01.png" class="w-4 h-4 md:w-6 md:h-6 mr-1" alt="telegram" />
            สมัครผ่านเทเลเกรม
          </a> -->
        </div>
      </div>
    </div>
  </main>
</template>

<style scope>
.btn-register {
  background: linear-gradient(180deg, #ffe66f, #fffb8f 31.25%, #debc50 70.31%, #fde26b),
    linear-gradient(180deg, #ffe66f, #fffb8f 31.25%, #debc50 70.31%, #fde26b);
}
.btn-line {
  background: linear-gradient(
      180deg,
      #b6b6b6,
      #fafbfc 18.75%,
      #e5e5e5 37.5%,
      #ececec 70.83%,
      #a0a0a0
    ),
    linear-gradient(180deg, #b6b6b6, #fafbfc 18.75%, #e5e5e5 37.5%, #ececec 70.83%, #a0a0a0);
}
</style>
