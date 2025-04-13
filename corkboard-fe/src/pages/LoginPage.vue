<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex flex-center">
        <q-card class="q-pa-md" style="min-width: 300px; max-width: 400px;">
          <q-card-section>
            <div class="text-h6">Login</div>
          </q-card-section>

          <q-card-section>
            <q-input v-model="email" label="Email" type="email" />
            <q-input v-model="password" label="Password" type="password" class="q-mt-sm" />
          </q-card-section>

          <q-card-actions align="right">
            <q-btn label="Login" color="primary" @click="() => void handleLogin()" />
          </q-card-actions>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { api } from 'boot/axios'
  import { Notify } from 'quasar'
  
  const router = useRouter()
  const email = ref('')
  const password = ref('')

  const handleLogin = async () => {
    try {
      const response = await api.post<{ user: { token: string } }>('/login', {
        email: email.value,
        password: password.value
      });

      const token = response.data.user.token;
      localStorage.setItem('token', token);
      api.defaults.headers.common.Authorization = `Bearer ${token}`;

      await router.push('/');
    } catch (err: unknown) {
      console.error(err);
      Notify.create({
        type: 'negative',
        message: 'Login failed. Please check your credentials.'
      });
    }
  }
</script>