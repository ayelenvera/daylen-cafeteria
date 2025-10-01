<template>
  <Head title="Factura - Cafetería Daylen" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Factura del Pedido</h2>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
          <Link :href="route('orders.index')" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 text-center">← Volver a Pedidos</Link>
          <button @click="downloadPDF" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 min-w-[140px] shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>📄 Descargar PDF</span>
          </button>
        </div>
      </div>
    </template>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden" id="invoice-content">
          <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-6 px-8">
            <div class="flex justify-between items-start">
              <div>
                <h1 class="text-2xl font-bold mb-1">☕ Daylen Cafetería</h1>
                <p class="text-amber-100">Sistema de Facturación</p>
                <p class="text-amber-100 text-sm mt-1">Factura Electrónica</p>
              </div>
              <div class="text-right">
                <div class="bg-white text-amber-800 px-3 py-1 rounded-lg font-bold text-lg">FACTURA</div>
                <p class="text-amber-100 text-sm mt-2">N° {{ order.id.toString().padStart(6, '0') }}</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              <!-- En la sección de Información de la Empresa, reemplazar: -->
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de la Empresa</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Empresa:</span> {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}</p>
                  <p><span class="font-medium">Dirección:</span> {{ $page.props.shopSettings?.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este' }}</p>
                  <p><span class="font-medium">Teléfono:</span> {{ $page.props.shopSettings?.phone || '+595 986 195914' }}</p>
                  <p><span class="font-medium">Email:</span> {{ $page.props.shopSettings?.email || 'daylencoffee@gmail.com' }}</p>
                  <p><span class="font-medium">RUC:</span> {{ $page.props.shopSettings?.ruc || '12345678-9' }}</p>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Cliente</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Cliente:</span> {{ order.user.name }}</p>
                  <p><span class="font-medium">Email:</span> {{ order.user.email }}</p>
                  <p><span class="font-medium">Nombre de Entrega:</span> {{ order.customer_name }}</p>
                  <p><span class="font-medium">Teléfono:</span> {{ order.phone }}</p>
                  <p><span class="font-medium">Dirección:</span> {{ order.address }}</p>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles del Pedido</h3>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit. (sin IVA)</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IVA Incluido</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in order.order_items" :key="item.id">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div>
                            <div class="text-sm font-medium text-gray-900">{{ item.product.name }}</div>
                            <div v-if="item.size || item.sugar" class="text-sm text-gray-500">
                              <span v-if="item.size">Tamaño: {{ item.size }}</span>
                              <span v-if="item.size && item.sugar"> • </span>
                              <span v-if="item.sugar">Azúcar: {{ item.sugar }}</span>
                            </div>
                            <!-- AGREGAR TOPPINGS Y ADDONS -->
                            <div v-if="(item.toppings && item.toppings.length > 0) || (item.addons && item.addons.length > 0)" class="text-xs text-gray-500 mt-1">
                              <div v-if="item.toppings && item.toppings.length > 0">
                                <span class="font-medium">Coberturas:</span> {{ item.toppings.join(', ') }}
                              </div>
                              <div v-if="item.addons && item.addons.length > 0">
                                <span class="font-medium">Agregados:</span> {{ item.addons.join(', ') }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.quantity }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₲ {{ Number(calculatePriceWithoutIVA(item.price)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">₲ {{ Number(calculateIVA(item.price)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">₲ {{ Number(item.subtotal).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-6">
              <div class="flex justify-end">
                <div class="w-64">
                  <div class="flex justify-between text-gray-600 mb-2">
                    <span>Subtotal (sin IVA):</span>
                    <span>₲ {{ Number(calculateSubtotalWithoutIVA(order)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  <div class="flex justify-between text-gray-600 mb-2">
                    <span>IVA (10% incluido):</span>
                    <span class="text-blue-600">₲ {{ Number(calculateTotalIVA(order)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  <div class="flex justify-between text-gray-600 mb-2">
                    <span>Descuento:</span>
                    <span>₲ 0</span>
                  </div>
                  <div class="border-t border-gray-200 pt-2">
                    <div class="flex justify-between text-lg font-bold text-gray-900">
                      <span>TOTAL (IVA incluido):</span>
                      <span class="text-amber-600">₲ {{ Number(order.total).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de Pago</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Método de Pago:</span> {{ order.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta' }}</p>
                  <p><span class="font-medium">Estado:</span>
                    <span :class="getStatusBadgeClass(order.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2">{{ getStatusText(order.status) }}</span>
                  </p>
                  <p v-if="order.payment_id"><span class="font-medium">ID de Pago:</span> {{ order.payment_id }}</p>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Pedido</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Fecha del Pedido:</span> {{ formatDate(order.created_at) }}</p>
                  <p><span class="font-medium">Notas:</span> {{ order.notes || 'Sin notas' }}</p>
                </div>
              </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-200">
              <div class="text-center text-gray-500 text-sm">
                <p>¡Gracias por su compra!</p>
                <p class="mt-1">Para consultas, contáctenos en daylencoffee@gmail.com</p>
                <p class="mt-2 text-xs">Factura generada automáticamente el {{ formatDate(new Date()) }}</p>
              </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-200">
              <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-red-800 mb-3">¿Necesitas tu factura en PDF?</h3>
                <p class="text-red-600 mb-4">Haz clic en el botón a continuación para descargar tu factura en formato PDF</p>
                <button @click="downloadPDF" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 mx-auto min-w-[200px] text-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  <span>📄 Descargar Factura PDF</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  order: {
    type: Object,
    required: true
  }
})

const calculatePriceWithoutIVA = (price) => {
  return Math.round(price / 1.10)
}

const calculateIVA = (price) => {
  return price - calculatePriceWithoutIVA(price)
}

const calculateSubtotalWithoutIVA = (order) => {
  if (!order || !order.order_items) return 0
  return order.order_items.reduce((total, item) => {
    return total + (calculatePriceWithoutIVA(item.price || 0) * (item.quantity || 1))
  }, 0)
}

const calculateTotalIVA = (order) => {
  if (!order || !order.order_items) return 0
  return order.order_items.reduce((total, item) => {
    return total + (calculateIVA(item.price || 0) * (item.quantity || 1))
  }, 0)
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-yellow-100 text-yellow-800'
    case 'paid': return 'bg-green-100 text-green-800'
    case 'cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const getStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente'
    case 'paid': return 'Pagado'
    case 'cancelled': return 'Cancelado'
    default: return status
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const downloadPDF = async () => {
  try {
    console.log('🎯 Iniciando conversión directa HTML a PDF para pedido:', props.order.id)
    if (!props.order || !props.order.id) {
      throw new Error('No se encontraron los datos del pedido. Por favor, recarga la página.')
    }
    const loadingToast = document.createElement('div')
    loadingToast.innerHTML = `
      <div style="
        position: fixed;
        top: 20px;
        right: 20px;
        background: #f59e0b;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
      ">
        <div style="width: 16px; height: 16px; border: 2px solid white; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        Generando PDF...
      </div>
      <style>
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      </style>
    `
    document.body.appendChild(loadingToast)
    const formatDateForPDF = (date) => {
      try {
        if (!date) return 'Fecha no disponible'
        return new Date(date).toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })
      } catch (error) {
        console.error('❌ Error formateando fecha:', error)
        return 'Fecha no disponible'
      }
    }
    console.log('📦 Cargando librerías para conversión HTML a PDF...')
    if (typeof window.html2canvas === 'undefined') {
      const canvasScript = document.createElement('script')
      canvasScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js'
      document.head.appendChild(canvasScript)
      await new Promise((resolve, reject) => {
        canvasScript.onload = resolve
        canvasScript.onerror = () => reject(new Error('No se pudo cargar html2canvas'))
        setTimeout(() => reject(new Error('Timeout cargando html2canvas')), 10000)
      })
    }
    if (typeof window.jspdf === 'undefined') {
      const pdfScript = document.createElement('script')
      pdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'
      document.head.appendChild(pdfScript)
      await new Promise((resolve, reject) => {
        pdfScript.onload = resolve
        pdfScript.onerror = () => reject(new Error('No se pudo cargar jsPDF'))
        setTimeout(() => reject(new Error('Timeout cargando jsPDF')), 10000)
      })
    }
    console.log('✅ Librerías cargadas exitosamente')
    const pdfContent = document.createElement('div')
    pdfContent.style.position = 'absolute'
    pdfContent.style.left = '-9999px'
    pdfContent.style.top = '-9999px'
    pdfContent.style.width = '794px'
    pdfContent.style.background = 'white'
    pdfContent.style.fontFamily = 'Arial, sans-serif'
    pdfContent.innerHTML = `
      <div style="font-family: Arial, sans-serif; color: #333; max-width: 794px; margin: 0 auto; background: white; padding: 25px;">
        <div style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: white; padding: 15px; text-align: center; margin-bottom: 15px; border-radius: 10px;">
          <h1 style="font-size: 20px; margin: 0; margin-bottom: 6px; font-weight: bold;">☕ Daylen Cafetería</h1>
          <p style="font-size: 12px; margin: 0; opacity: 0.9; margin-bottom: 8px;">Sistema de Facturación Electrónica</p>
          <div style="background: white; color: #d97706; padding: 6px 15px; border-radius: 3px; font-weight: bold; font-size: 14px; display: inline-block;">FACTURA N° ${props.order.id.toString().padStart(6, '0')}</div>
        </div>
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información de la Empresa</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Empresa:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">{{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">RUC:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">{{ $page.props.shopSettings?.ruc || '12345678-9' }}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Dirección:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">{{ $page.props.shopSettings?.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este' }}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Teléfono:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">{{ $page.props.shopSettings?.phone || '+595 986 195914' }}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Email:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">{{ $page.props.shopSettings?.email || 'daylencoffee@gmail.com' }}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Fecha:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${formatDateForPDF(new Date())}</td>
            </tr>
          </table>
        </div>
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información del Cliente</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Cliente:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.user?.name || 'Cliente no disponible'}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">Email:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.user?.email || 'Email no disponible'}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Nombre de Entrega:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.customer_name || 'No especificado'}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Teléfono:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.phone || 'No especificado'}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Dirección:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;" colspan="3">${props.order.address || 'No especificada'}</td>
            </tr>
          </table>
        </div>
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Detalles del Pedido</h3>
          <table style="width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; margin-bottom: 10px;">
            <thead>
              <tr style="background: #f9fafb;">
                <th style="padding: 6px; text-align: left; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; font-size: 9px;">Producto</th>
                <th style="padding: 6px; text-align: center; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 60px; font-size: 9px;">Cant.</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">Precio sin IVA</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">IVA Incluido</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${props.order.order_items && props.order.order_items.length > 0 ? props.order.order_items.map(item => `
                <tr>
                  <td style="padding: 6px; border: 1px solid #e5e7eb; color: #374151; font-size: 9px;">
                    <strong>${item.product?.name || 'Producto eliminado'}</strong>
                    ${item.size || item.sugar ? `<br><small style="color: #6b7280; font-size: 8px;">${item.size || ''} ${item.size && item.sugar ? '•' : ''} ${item.sugar || ''}</small>` : ''}
                    <!-- AGREGAR TOPPINGS Y ADDONS AL PDF -->
                    ${(item.toppings && item.toppings.length > 0) || (item.addons && item.addons.length > 0) ? `
                      <br>
                      <small style="color: #6b7280; font-size: 7px;">
                        ${item.toppings && item.toppings.length > 0 ? `<strong>Coberturas:</strong> ${item.toppings.join(', ')}` : ''}
                        ${item.toppings && item.toppings.length > 0 && item.addons && item.addons.length > 0 ? ' • ' : ''}
                        ${item.addons && item.addons.length > 0 ? `<strong>Agregados:</strong> ${item.addons.join(', ')}` : ''}
                      </small>
                    ` : ''}
                  </td>
                  <td style="padding: 6px; text-align: center; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">${item.quantity || 0}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">₲ ${Number(calculatePriceWithoutIVA(item.price || 0)).toLocaleString('es-PY')}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #1d4ed8; font-size: 9px; font-weight: 500;">₲ ${Number(calculateIVA(item.price || 0)).toLocaleString('es-PY')}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">₲ ${Number(item.subtotal || 0).toLocaleString('es-PY')}</td>
                </tr>
              `).join('') : '<tr><td colspan="5" style="padding: 12px; text-align: center; color: #999; border: 1px solid #e5e7eb; font-size: 9px;">No hay productos disponibles</td></tr>'}
            </tbody>
          </table>
        </div>
        <div style="background: #f9fafb; padding: 12px; margin: 12px 0; border-radius: 4px; border: 1px solid #e5e7eb;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">Subtotal (sin IVA):</span>
            <span style="color: #374151; font-weight: 500;">₲ ${Number(calculateSubtotalWithoutIVA(props.order)).toLocaleString('es-PY')}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">IVA Total (10% incluido):</span>
            <span style="color: #1d4ed8; font-weight: 500;">₲ ${Number(calculateTotalIVA(props.order)).toLocaleString('es-PY')}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">Descuento:</span>
            <span style="color: #374151; font-weight: 500;">₲ 0</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; color: #d97706; border-top: 2px solid #d97706; padding-top: 6px; margin-top: 6px;">
            <span>TOTAL (IVA incluido):</span>
            <span>₲ ${Number(props.order.total || 0).toLocaleString('es-PY')}</span>
          </div>
        </div>
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información de Pago</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Método de Pago:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta'}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">Estado:</td>
              <td style="padding: 3px 0; font-weight: bold; font-size: 10px;" class="${getStatusBadgeClass(props.order.status).replace('bg-', '').replace('text-', '').split(' ')[0]}">${getStatusText(props.order.status)}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Fecha del Pedido:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${formatDateForPDF(props.order.created_at)}</td>
              ${props.order.payment_id ? `<td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">ID de Pago:</td><td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.payment_id}</td>` : '<td></td><td></td>'}
            </tr>
            ${props.order.notes ? `
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Notas:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;" colspan="3">${props.order.notes}</td>
            </tr>
            ` : ''}
          </table>
        </div>
        <div style="text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">
          <p style="font-size: 12px; font-weight: bold; color: #d97706; margin-bottom: 4px;">¡Gracias por su compra en Daylen Cafetería!</p>
          <p style="font-size: 9px; margin-bottom: 3px;">Para consultas, contáctenos en daylencoffee@gmail.com</p>
          <p style="font-size: 8px; margin-bottom: 3px;">Teléfono: +595 986 195914</p>
          <p style="font-size: 7px; margin-top: 8px; color: #9ca3af;">Factura generada automáticamente el ${formatDateForPDF(new Date())}</p>
        </div>
      </div>
    `
    document.body.appendChild(pdfContent)
    await new Promise(resolve => setTimeout(resolve, 1000))
    console.log('📸 Capturando contenido con html2canvas...')
    const canvas = await window.html2canvas(pdfContent, {
      scale: 2.5,
      useCORS: true,
      allowTaint: true,
      backgroundColor: '#ffffff',
      width: 794,
      height: 1123
    })
    console.log('✅ Contenido capturado exitosamente')
    console.log('📄 Creando PDF en formato A4 optimizado con jsPDF...')
    const { jsPDF } = window.jspdf
    const pdf = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4',
      compress: true
    })
    const imgWidth = 210
    const pageHeight = 297
    const imgHeight = (canvas.height * imgWidth) / canvas.width
    let heightLeft = imgHeight
    const imgData = canvas.toDataURL('image/png', 0.95)
    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight)
    heightLeft -= pageHeight
    while (heightLeft >= 0) {
      pdf.addPage()
      pdf.addImage(imgData, 'PNG', 0, -pageHeight + heightLeft, imgWidth, imgHeight)
      heightLeft -= pageHeight
    }
    console.log('✅ PDF en formato A4 optimizado creado exitosamente')
    console.log('⬇️ Descargando PDF...')
    pdf.save(`factura-${props.order.id}.pdf`)
    document.body.removeChild(pdfContent)
    document.body.removeChild(loadingToast)
    console.log('✅ PDF descargado exitosamente')
  } catch (error) {
    console.error('❌ Error generando PDF:', error)
    const loadingToast = document.querySelector('[style*="Generando PDF"]')
    if (loadingToast) {
      document.body.removeChild(loadingToast.parentElement)
    }
  }
}
</script>

<style>
.invoice-table {
  min-width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
.invoice-table thead {
  background-color: #f9fafb;
}
.invoice-table th {
  padding: 12px 24px;
  text-align: left;
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid #e5e7eb;
}
.invoice-table td {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
  color: #374151;
}
.invoice-total {
  display: flex;
  justify-content: space-between;
  font-size: 18px;
  font-weight: bold;
  color: #374151;
}
</style>