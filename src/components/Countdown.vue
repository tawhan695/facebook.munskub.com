<template>
  <div class="grid grid-cols-3 gap-1.5 md:gap-x-3 text-center">
    <div class="bg-red-600 rounded-lg text-white flex flex-col p-4">
      <span class="font-mono text-3xl md:text-4xl">{{ formattedHours }}</span>
      <span class="text-sm md:text-base">ชั่วโมง</span>
    </div>
    <div class="bg-red-600 rounded-lg text-white flex flex-col p-4">
      <span class="font-mono text-3xl md:text-4xl">{{ formattedMinutes }}</span>
      <span class="text-sm md:text-base">นาที</span>
    </div>
    <div class="bg-red-600 rounded-lg text-white flex flex-col p-4">
      <span class="font-mono text-3xl md:text-4xl">{{ formattedSeconds }}</span>
      <span class="text-sm md:text-base">วินาที</span>
    </div>
  </div>
</template>

<script lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';

export default {
  setup() {
    const hours = ref(1);
    const minutes = ref(59);
    const seconds = ref(59);
    let intervalId:any = null;

    const updateCountdown = () => {
      if (seconds.value > 0) {
        seconds.value--;
      } else if (minutes.value > 0) {
        seconds.value = 59;
        minutes.value--;
      } else if (hours.value > 0) {
        seconds.value = 59;
        minutes.value = 59;
        hours.value--;
      } else {
        clearInterval(intervalId);
      }
    };

    const formattedHours = computed(() => String(hours.value).padStart(2, '0'));
    const formattedMinutes = computed(() => String(minutes.value).padStart(2, '0'));
    const formattedSeconds = computed(() => String(seconds.value).padStart(2, '0'));

    onMounted(() => {
      intervalId = setInterval(updateCountdown, 1000);
    });

    onUnmounted(() => {
      clearInterval(intervalId);
    });

    return {
      formattedHours,
      formattedMinutes,
      formattedSeconds,
    };
  },
};
</script>
