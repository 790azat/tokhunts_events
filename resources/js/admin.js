import { upload } from '@vercel/blob/client';

// Direct browser → Vercel Blob uploads for the admin panel (bypasses Vercel's 4.5 MB request limit).
// Usage: <div x-data="blobUploader({ folder: 'works', onUploaded: (kind, url) => $wire.addBlob(kind, url) })">
document.addEventListener('alpine:init', () => {
    window.Alpine.data('blobUploader', ({ folder, onUploaded }) => ({
        queue: [],
        error: null,

        get busy() {
            return this.queue.some((item) => item.progress < 100 && !item.failed);
        },

        async pick(event) {
            const files = Array.from(event.target.files || []);
            event.target.value = '';
            await Promise.all(files.map((file) => this.send(file)));
        },

        async send(file) {
            const item = { name: file.name, progress: 0, failed: false };
            this.queue.push(item);
            const entry = this.queue[this.queue.length - 1];
            const safeName = file.name.toLowerCase().replace(/[^a-z0-9.\-_]+/g, '-').replace(/^-+/, '') || 'file';

            try {
                const blob = await upload(`${folder}/${safeName}`, file, {
                    access: 'public',
                    handleUploadUrl: '/admin/blob-upload',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    multipart: file.size > 20 * 1024 * 1024,
                    onUploadProgress: ({ percentage }) => { entry.progress = Math.min(99, Math.round(percentage)); },
                });
                await onUploaded(file.type.startsWith('video/') ? 'video' : 'image', blob.url);
                entry.progress = 100;
                setTimeout(() => { this.queue = this.queue.filter((i) => i !== entry); }, 1500);
            } catch (e) {
                entry.failed = true;
                this.error = `${file.name}: ${e.message}`;
            }
        },
    }));
});
