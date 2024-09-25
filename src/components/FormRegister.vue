<script lang="ts">
import axios from 'axios'
import { ref } from 'vue'
import { toast } from 'vue3-toastify'

export default {
  props: {
    dataRegis: {
      type: Array as any,
      default: null
    },
    fetchRegis: {
      type: Function,
      default: null
    }
  },
  setup(props) {
    const phoneFormat = ref('')
    const phone = ref('')
    const password = ref('')
    const confirm_password = ref('')
    const loading = ref(false)
     const loadingOtp = ref(false)
    const closePhone = ref<boolean>(false)
    const otpRef = ref<string | null>(null)
    const tokenOtp = ref<string | null>(null)
    const otp = ref('')

    const submitForm = async () => {
      if (password.value != confirm_password.value) {
        toast('รหัสผ่านไม่ตรงกัน', { type: 'error' })
        return
      }
      if (password.value.length < 8) {
        toast('รหัสผ่านต้องมี 8 ตัวขึ้นไป', { type: 'error' })
        return
      }

      loading.value = true
      fetch(
        import.meta.env.VITE_SHEET_URL + '?phone=' + phone.value + '&password=' + password.value,
        {
          method: 'POST',
          mode: 'no-cors',
          headers: {
            'Content-Type': 'application/json'
          }
        }
      )
        .then((res) => {
          loading.value = false
          toast('สมัครสมาชิกสําเร็จ', { type: 'success' })
          phoneFormat.value = ''
          phone.value = ''
          password.value = ''
          confirm_password.value = ''
          props.fetchRegis()
          closePhone.value = false
          setTimeout(() => {
            window.location.href = 'https://lin.ee/mUDuTLy'
          }, 1000);
        })
        .catch(() => {
          loading.value = false
          toast('เกิดข้อผิดพลาดกรุณาลองใหม่ภายหลัง', { type: 'error' })
        })
    }

    const handlePhoneNumber = ({ event, dataRegis }: { event: any; dataRegis: any }) => {
      const number = event.target.value.replace(/\D/g, '').slice(0, 10)
      phone.value = number
      phoneFormat.value = formatPhoneNumber(number)
      if (phone.value.length === 10) {
        const result = dataRegis.find((item: any) => item == phone.value)
        if (result) {
          toast('เบอร์โทรศัพท์นี้มีอยู่ในระบบแล้ว', { type: 'error' })
          return
        }
        handleRequestOtp()
      }
    }

    const handleRequestOtp = () => {
      closePhone.value = true
      const options: RequestInit = {
        method: 'POST',
        headers: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          key: import.meta.env.VITE_OTP_KEY,
          token: import.meta.env.VITE_OTP_SECRET,
          phone: phone.value
        })
      }
      fetch('https://lazada.munskub.com/otp/request_otp.php', options)
        .then((response) => response.json())
        .then((response) => {
          if (response.status == 'success') {
            toast('กรุณายืนยัน OTP', { type: 'success' })
            otpRef.value = response.refno
            tokenOtp.value = response.token
          } else {
            toast(response.errors[0].message || 'เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', {
              type: 'error'
            })
            closePhone.value = false
          }
        })
        .catch((err) => {
          console.log(err)
          toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', { type: 'error' })
          closePhone.value = false
        })
    }

    const handleSubmitOtp = () => {
      if (!otp.value) {
        toast('กรุณาระบุ OTP', { type: 'error' })
        return
      }
      if (!tokenOtp.value || !otpRef.value) {
        toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', { type: 'error' })
        return
      }
      const options: RequestInit = {
        method: 'POST',
        headers: {
          accept: 'application/json',
          'content-type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          key: import.meta.env.VITE_OTP_KEY,
          token: import.meta.env.VITE_OTP_SECRET,
          otp_ref: tokenOtp.value,
          otp: otp.value
        })
      }
      loadingOtp.value = true
      fetch('https://lazada.munskub.com/otp/verify_otp.php', options)
        .then((response) => response.json())
        .then((response) => {          
          loadingOtp.value = false
          if (response.status == 'success') {
            toast(response.message || 'ยืนยัน OTP สําเร็จ', { type: 'success' })
            otpRef.value = null
            tokenOtp.value = null
          } else {
            toast(response.errors[0].message, {
              type: 'error'
            })
          }
        })
        .catch((err) => {   
    
                 
          loadingOtp.value = false
          toast('รหัส otp ไม่ถูกต้อง', { type: 'error' })
        })
    }

    const handleRequestOtp2Pro = () => {
      closePhone.value = true
      const config = {
        method: 'post',
        url: 'https://portal.sms2pro.com/sms-api/otp-sms/send',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${import.meta.env.VITE_OTP_2PRO_KEY}`
        },
        data: JSON.stringify({
          recipient: phone.value,
          sender_name: 'senderName',
          ref_code: '',
          digit: '',
          validity: '',
          custom_message: ''
        })
      }
      axios(config)
        .then((response: any) => {
          if (response.status == 'Failed') {
            toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', {
              type: 'error'
            })
            closePhone.value = false
          } else {
            toast('กรุณายืนยัน OTP', { type: 'success' })
            otpRef.value = response.refno
            tokenOtp.value = response.token
          }
        })
        .catch((err) => {
          toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', { type: 'error' })
          closePhone.value = false
        })
    }

    const handleSubmitOtp2Pro = () => {
      if (!otp.value) {
        toast('กรุณาระบุ OTP', { type: 'error' })
        return
      }
      if (!tokenOtp.value || !otpRef.value) {
        toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', { type: 'error' })
        return
      }

      const config = {
        method: 'post',
        url: 'https://portal.sms2pro.com/sms-api/otp-sms/verify',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${import.meta.env.VITE_OTP_2PRO_KEY}`
        },
        data: JSON.stringify({
          token: tokenOtp.value,
          otp_code: otp.value,
          ref_code: otpRef.value
        })
      }

      axios(config)
        .then((response: any) => {
          if (response.status == 'Failed') {
            toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', {
              type: 'error'
            })
          } else {
            toast(response.message || 'ยืนยัน OTP สําเร็จ', { type: 'success' })
            otpRef.value = null
            tokenOtp.value = null
          }
        })
        .catch((err) => toast('เกิดข้อผิดผลาดกรุณาลองใหม่ภายหลัง', { type: 'error' }))
    }

    const formatPhoneNumber = (number: string) => {
      const cleanedNumber = number.replace(/[^\d]/g, '')
      const match = cleanedNumber.match(/^(\d{3})(\d{0,3})(\d{0,4})$/)
      if (match) {
        return `${match[1]}${match[2] ? '-' : ''}${match[2]}${match[3] ? '-' : ''}${match[3]}`
      }
      return number
    }

    return {
      submitForm,
      handlePhoneNumber,
      phoneFormat,
      password,
      loading,
      closePhone,
      otpRef,
      otp,
      loadingOtp,
      tokenOtp,
      confirm_password,
      handleSubmitOtp
    }
  }
}
</script>

<template >
  <form
    @submit.prevent="submitForm()"
    class="rounded-xl border border-gray-500 bg-black p-2.5 md:p-5 max-w-md mx-auto"
  >
    <p class="text-center text-white text-xl md:text-2xl pb-4">สมัครสมาชิก</p>

    <label class="input input-sm input-bordered flex items-center gap-2">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
      >
        <path
          d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z"
        />
      </svg>
      <input
        :disabled="loading || closePhone"
        v-model="phoneFormat"
        @input="handlePhoneNumber({ event: $event, dataRegis: dataRegis })"
        required
        class="grow"
        maxlength="12"
        placeholder="กรุณากรอกเบอร์โทรศัพท์"
      />
    </label>
    <p class="text-red-500 text-sm">กรุณากรอกเบอร์โทรศัพท์เพื่อสมัครสมาชิก</p>

    <div class="mt-5" v-if="!!otpRef && !!tokenOtp">
      <p class="text-center">รหัสอ่างอิง : {{ otpRef }}</p>
      <label class="input input-sm input-bordered flex items-center gap-0.5 pr-[2px] pl-[14px]">
        <input v-model="otp" placeholder="OTP" class="w-full h-full" maxlength="12" />
        <button
          @click="handleSubmitOtp"
          :disabled="!otp || loadingOtp"
          type="button"
          class="flex-none flex items-center gap-x-2 disabled:grayscale disabled:scale-100 bg-blue-500 text-white rounded-md px-6 py-1 text-sm hover:bg-blue-400 duration-200 active:scale-95"
        >
          <span v-if="loadingOtp" class="loading loading-spinner text-warning loading-sm"></span>
          <p v-else>ยืนยัน</p>
        </button>
      </label>
    </div>

    <label class="input input-sm input-bordered flex items-center gap-2 my-5">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
      >
        <path
          fill-rule="evenodd"
          d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
          clip-rule="evenodd"
        />
      </svg>
      <input
        :disabled="loading || !!otpRef"
        v-model="password"
        type="password"
        class="grow"
        required
        placeholder="ตั้งรหัสผ่าน 8 ตัวอักษรขึ้นไป"
      />
    </label>

    <label class="input input-sm input-bordered flex items-center gap-2">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
      >
        <path
          fill-rule="evenodd"
          d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
          clip-rule="evenodd"
        />
      </svg>
      <input
        :disabled="loading || !!otpRef"
        v-model="confirm_password"
        type="password"
        class="grow"
        placeholder="ยืนยันรหัสผ่าน"
      />
    </label>
    <p class="text-red-500 text-sm">กรุณากรอกรหัสผ่านให้ถูกต้อง</p>

    <button
      :disabled="loading || !!otpRef"
      type="submit"
      class="text-black mt-6 mx-auto w-[6.5rem] rounded-lg h-[2.25rem] active:scale-95 duration-200 flex items-center justify-center"
      :class="[
        loading || !!otpRef ? 'bg-white/30 cursor-default pointer-events-none' : 'btn-submit'
      ]"
    >
      <span v-if="loading" class="loading loading-spinner text-warning loading-sm"></span>
      <p v-else>ยืนยัน</p>
    </button>
  </form>
</template>

<style scoped>
.btn-submit {
  background: linear-gradient(180deg, #ffe66f, #fffb8f 31.25%, #debc50 70.31%, #fde26b),
    linear-gradient(180deg, #ffe66f, #fffb8f 31.25%, #debc50 70.31%, #fde26b);
}
</style>