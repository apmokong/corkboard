<template>
  <q-dialog v-model="dialogVisible">
    <q-card style="width: 100%">
      <q-card-section>
        <div class="text-h6">{{ isEditMode ? 'Edit Blog' : 'Create Blog' }}</div>
      </q-card-section>

      <q-card-section>
        <q-input v-model="form.title" label="Blog Title" autofocus />
        <!-- <q-input
          v-model="form.content"
          label="Blog Content"
          type="textarea"
          rows="4"
          class="q-mt-sm"
        /> -->
        <q-editor v-model="form.content" min-height="15rem" class="q-mt-sm"/>
      </q-card-section>

      <q-card-actions>
        <q-btn label="Cancel" color="negative" @click="handleCancel" />
        <q-btn
          :label="isEditMode ? 'Update' : 'Create'"
          color="primary"
          @click="submitForm"
          :loading="isSubmitting"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
  import { ref, watch, computed } from 'vue';
  // import { useRouter } from 'vue-router';
  import { api } from 'boot/axios';
  import type { Blog } from 'src/types/blog';

  interface Props {
    modelValue: boolean;
    selectedBlog: Blog | null;
  }

  const props = defineProps<Props>();
  const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'refresh'): void;
  }>();

  // const router = useRouter();

  const dialogVisible = computed({
    get: () => props.modelValue,
    set: (val: boolean) => emit('update:modelValue', val),
  });

  const form = ref<Partial<Blog>>({
    title: '',
    content: '',
  });

  const isEditMode = ref(false);
  const isSubmitting = ref(false);

  watch(
    () => props.selectedBlog,
    (blog) => {
      if (dialogVisible.value && blog) {
        isEditMode.value = true;
        form.value = { ...blog };
      } else {
        isEditMode.value = false;
        form.value = { title: '', content: '' };
      }
    },
    { immediate: true }
  );

  const handleCancel = () => {
    dialogVisible.value = false;
  };

  const submitForm = async () => {
    isSubmitting.value = true;
    try {
      if (isEditMode.value && form.value.id) {
        await api.put(`/blogs/${form.value.id}`, form.value);
      } else {
        await api.post('/blogs', form.value);
      }

      emit('refresh'); // Let parent refetch blogs
      dialogVisible.value = false;
    } catch (error) {
      console.error('Failed to submit blog form:', error);
    } finally {
      isSubmitting.value = false;
    }
  };
</script>