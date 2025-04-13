<template>
  <q-page class="q-pa-md">
    <div v-if="blog">
      <h5>{{ blog.title }}</h5>
      <div class="text-subtitle2 text-grey-7 q-mb-sm">
        <strong>{{ blog.creator.name }}</strong> · {{ blog.formatted_created_at }}
      </div>

      <hr>
      
      <div v-html="blog.content" class="q-mt-md" />
    </div>

    <q-inner-loading :showing="loading">
      <q-spinner-gears size="50px" color="primary" />
    </q-inner-loading>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { api } from 'boot/axios';
import type { Blog } from 'src/types/blog';

const route = useRoute();
const blog = ref<Blog | null>(null);
const loading = ref(true);

const fetchBlog = async (): Promise<void> => {
  try {
    const id = Array.isArray(route.params.id) ? route.params.id[0] : route.params.id;

    if (!id) {
      throw new Error('Invalid blog ID.');
    }

    console.log('Authorization header:', api.defaults.headers.common.Authorization);

    const res = await api.get<{ blog: Blog }>(`/blogs/${id}/view`);

    console.log("result", res)

    blog.value = res.data.blog;
  
  } catch (error) {
  
    console.error('Error fetching blog:', error);
  
  } finally {
  
    loading.value = false;
  }
};

onMounted(() => {
  void fetchBlog();
});
</script>
