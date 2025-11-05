import './bootstrap'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

createInertiaApp({
  resolve: name => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})

// // Désactiver le cache - recharger les données à chaque navigation
// import { router } from '@inertiajs/inertia'

// router.on('before', (event) => {
//   // Forcer le rechargement des données
//   event.detail.visit.preserveState = false
// })