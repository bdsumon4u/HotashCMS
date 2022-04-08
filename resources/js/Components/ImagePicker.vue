<template>
    <div class="relative mt-5">
        <h2 class="flex bg-white border-2 border-gray-300 border-dashed py-1 px-2 rounded-md absolute left-0 -top-3">
            <label class="text-sm font-bold text-gray-500 tracking-wide" :for="id">{{ ucwords(name, '_') }}</label>
        </h2>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
                <div class="flex justify-center text-sm text-gray-600">
                    <label :for="id" class="relative cursor-pointer bg-white rounded-sm font-medium text-indigo-600 hover:text-indigo-500">
                        <img
                            v-if="file"
                            class="mx-auto border"
                            :src="file"
                            alt="Image" />
                        <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </label>
                    <input type="file" ref="fileInput" :id="id" :name="name" class="sr-only" @change="pickFile" :disabled="disabled" />
                </div>
                <slot name="message"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ImagePicker",
    props: ['name', 'value', 'disabled'],
    computed: {
        id() {
            return this.name.replace('_', '-');
        }
    },
    data() {
        return {
            file: this.value ?? null,
        };
    },
    methods: {
        pickFile () {
            const photo = this.$refs.fileInput.files[0];
            if (photo) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.file = e.target.result;
                };
                reader.readAsDataURL(photo);
            }
            this.$emit('pick', photo);
        }
    }
}
</script>
