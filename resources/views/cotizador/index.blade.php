@extends('layouts.app')
@section('title', 'Cotizador Inteligente de Logística Internacional')

@section('content')
<div class="hero-bg min-h-screen" x-data="cotizador()">

  {{-- HERO --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-8 text-center">
    <div class="inline-flex items-center gap-2 bg-gold-400/10 border border-gold-400/30 rounded-full px-4 py-1.5 mb-6">
      <span class="w-2 h-2 bg-gold-400 rounded-full animate-pulse"></span>
      <span class="text-xs font-medium text-gold-400 uppercase tracking-widest">Powered by Gemini 2.0 Flash</span>
    </div>
    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4">
      Cotiza tu carga<br>
      <span class="text-gold-gradient">en segundos con IA</span>
    </h1>
    <p class="text-gray-400 text-lg max-w-2xl mx-auto">
      Tarifas estimadas de flete marítimo FCL y LCL desde puertos colombianos hacia el mundo,
      con navieras recomendadas, documentos y alertas de comercio exterior.
    </p>
  </section>

  {{-- FORMULARIO --}}
  <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="glass-card rounded-2xl p-8">
      <h2 class="text-xl font-bold text-gold-400 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Datos de la Carga
      </h2>

      <form @submit.prevent="cotizar()" novalidate>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Origen --}}
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Puerto de Origen *</label>
            <select x-model="form.origen" id="origen"
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
              <option value="">Selecciona un puerto...</option>
              <option value="Cartagena (CO CTG)">Cartagena (CO CTG)</option>
              <option value="Buenaventura (CO BUN)">Buenaventura (CO BUN)</option>
              <option value="Barranquilla (CO BAQ)">Barranquilla (CO BAQ)</option>
              <option value="Santa Marta (CO SMR)">Santa Marta (CO SMR)</option>
              <option value="otro">Otro puerto...</option>
            </select>
            <input x-show="form.origen === 'otro'" x-model="form.origenLibre"
              placeholder="Escribe el puerto de origen"
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm mt-2">
          </div>

          {{-- Destino --}}
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Puerto de Destino *</label>
            <input x-model="form.destino" id="destino" list="puertos-destino"
              placeholder="Ej: Rotterdam, Houston, Miami..."
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
            <datalist id="puertos-destino">
              <option value="Rotterdam (NL RTM)">
              <option value="Houston (US HOU)">
              <option value="Miami (US MIA)">
              <option value="Shanghai (CN SHA)">
              <option value="Balboa (PA BLB)">
              <option value="Manzanillo (MX ZLO)">
              <option value="Hamburgo (DE HAM)">
              <option value="Valencia (ES VLC)">
              <option value="New York (US NYC)">
              <option value="Savannah (US SAV)">
            </datalist>
          </div>

          {{-- Tipo de Carga --}}
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Tipo de Carga *</label>
            <select x-model="form.tipo_carga" id="tipo_carga"
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
              <option value="">Selecciona tipo...</option>
              <option value="FCL20">FCL 20' — Contenedor completo 20 pies</option>
              <option value="FCL40">FCL 40' — Contenedor completo 40 pies</option>
              <option value="FCL40HC">FCL 40' HC — High Cube</option>
              <option value="LCL">LCL — Carga suelta (por CBM)</option>
            </select>
          </div>

          {{-- Tipo Mercancía --}}
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Tipo de Mercancía *</label>
            <input x-model="form.tipo_mercancia" id="tipo_mercancia"
              placeholder="Ej: Calzado, maquinaria, alimentos..."
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
          </div>

          {{-- Peso --}}
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Peso Total (kg) *</label>
            <input type="number" x-model="form.peso" id="peso"
              placeholder="Ej: 12000" min="0.01" step="0.01"
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
          </div>

          {{-- Volumen LCL --}}
          <div x-show="form.tipo_carga === 'LCL'" x-cloak>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Volumen (CBM) *</label>
            <input type="number" x-model="form.volumen" id="volumen"
              placeholder="Ej: 8.5" min="0.001" step="0.001"
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
          </div>

          {{-- Valor Comercial --}}
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">
              Valor Comercial (USD) <span class="text-gray-500 font-normal">— opcional</span>
            </label>
            <input type="number" x-model="form.valor_comercial" id="valor_comercial"
              placeholder="Ej: 45000" min="0"
              class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
          </div>

          {{-- Seguro --}}
          <div class="flex items-center gap-3 pt-6">
            <input type="checkbox" x-model="form.requiere_seguro" id="requiere_seguro"
              class="w-4 h-4 rounded accent-gold-400 cursor-pointer">
            <label for="requiere_seguro" class="text-sm text-gray-300 cursor-pointer">
              ¿Requiere seguro de carga? 🔒
            </label>
          </div>
        </div>

        {{-- Error --}}
        <div x-show="errorMsg" x-cloak class="mt-4 p-4 rounded-lg bg-red-900/30 border border-red-500/30">
          <p class="text-red-400 text-sm" x-text="errorMsg"></p>
        </div>

        {{-- Submit --}}
        <div class="mt-8 flex justify-center">
          <button type="submit" id="btn-cotizar"
            :disabled="loading"
            class="btn-gold text-navy-900 font-bold text-base px-10 py-3.5 rounded-xl flex items-center gap-3 disabled:opacity-60 disabled:cursor-not-allowed">
            <span x-show="!loading">🚀 Cotizar con IA</span>
            <span x-show="loading" x-cloak class="flex items-center gap-2">
              <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              Analizando con IA...
            </span>
          </button>
        </div>
      </form>
    </div>
  </section>

  {{-- RESULTADOS --}}
  <section x-show="resultado" x-cloak
    class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

    <div class="flex items-center gap-3 mb-8">
      <div class="h-px flex-1 bg-gold-400/20"></div>
      <h2 class="text-lg font-bold text-gold-400 flex items-center gap-2">
        <span>✨</span> Cotización Generada por IA
      </h2>
      <div class="h-px flex-1 bg-gold-400/20"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

      {{-- Tarifa --}}
      <div class="result-card glass-card rounded-2xl p-6 sm:col-span-2">
        <div class="flex items-start justify-between flex-wrap gap-3">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">💰 Tarifa Estimada</p>
            <p class="text-3xl font-extrabold text-gold-400"
              x-text="resultado?.tarifa_estimada ? 'USD $' + resultado.tarifa_estimada.minimo.toLocaleString() + ' — $' + resultado.tarifa_estimada.maximo.toLocaleString() : '—'">
            </p>
            <p class="text-sm text-gray-400 mt-1" x-text="resultado?.tarifa_estimada?.descripcion"></p>
          </div>
          <div class="text-right text-xs text-gray-600">
            <p x-text="form.tipo_carga_label"></p>
            <p x-text="(form.origen === 'otro' ? form.origenLibre : form.origen) + ' → ' + form.destino"></p>
          </div>
        </div>
      </div>

      {{-- Tránsito --}}
      <div class="result-card glass-card rounded-2xl p-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">⏱️ Tiempo de Tránsito</p>
        <p class="text-2xl font-bold text-white"
          x-text="resultado?.tiempo_transito ? resultado.tiempo_transito.minimo + ' — ' + resultado.tiempo_transito.maximo + ' ' + resultado.tiempo_transito.unidad : '—'">
        </p>
        <p class="text-sm text-gray-400 mt-1" x-text="resultado?.tiempo_transito?.descripcion"></p>
      </div>

      {{-- Navieras --}}
      <div class="result-card glass-card rounded-2xl p-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">🚢 Navieras Recomendadas</p>
        <template x-for="n in (resultado?.navieras || [])" :key="n.nombre">
          <div class="mb-2">
            <p class="font-semibold text-white text-sm" x-text="n.nombre"></p>
            <p class="text-xs text-gray-500" x-text="n.servicio"></p>
          </div>
        </template>
      </div>

      {{-- Documentos --}}
      <div class="result-card glass-card rounded-2xl p-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">📄 Documentos Requeridos</p>
        <ul class="space-y-1.5">
          <template x-for="doc in (resultado?.documentos || [])" :key="doc">
            <li class="flex items-start gap-2 text-sm text-gray-300">
              <span class="text-gold-400 mt-0.5 flex-shrink-0">•</span>
              <span x-text="doc"></span>
            </li>
          </template>
        </ul>
      </div>

      {{-- Alertas --}}
      <div x-show="resultado?.alertas?.length" class="result-card glass-card rounded-2xl p-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">⚠️ Alertas y Oportunidades</p>
        <div class="space-y-3">
          <template x-for="alerta in (resultado?.alertas || [])" :key="alerta.titulo">
            <div :class="alerta.tipo === 'oportunidad'
              ? 'bg-gold-400/10 border border-gold-400/30 rounded-lg p-3'
              : 'bg-red-900/20 border border-red-500/30 rounded-lg p-3'">
              <p class="text-sm font-semibold" :class="alerta.tipo === 'oportunidad' ? 'text-gold-400' : 'text-red-400'"
                x-text="alerta.titulo"></p>
              <p class="text-xs text-gray-400 mt-1" x-text="alerta.descripcion"></p>
            </div>
          </template>
        </div>
      </div>

      {{-- Próximos Pasos --}}
      <div class="result-card glass-card rounded-2xl p-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">🔗 Próximos Pasos</p>
        <ol class="space-y-2">
          <template x-for="(paso, i) in (resultado?.proximos_pasos || [])" :key="i">
            <li class="flex items-start gap-3 text-sm text-gray-300">
              <span class="flex-shrink-0 w-5 h-5 rounded-full bg-gold-400/20 text-gold-400 text-xs flex items-center justify-center font-bold"
                x-text="i + 1"></span>
              <span x-text="paso"></span>
            </li>
          </template>
        </ol>
      </div>
    </div>

    {{-- CTA --}}
    <div class="mt-10 text-center">
      <p class="text-gray-400 text-sm mb-4">
        ¿Esta cotización se ajusta a tus necesidades? Solicita una cotización oficial de nuestros expertos.
      </p>
      <button @click="abrirModal()" id="btn-cotizacion-formal"
        class="btn-gold text-navy-900 font-bold text-base px-10 py-4 rounded-xl inline-flex items-center gap-2">
        📬 Solicitar Cotización Formal
      </button>
    </div>
  </section>

  {{-- MODAL CONTACTO --}}
  <div x-show="modal" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);">

    <div @click.outside="modal = false"
      class="glass-card rounded-2xl p-8 w-full max-w-md relative animate-fade-in">

      <button @click="modal = false"
        class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <h3 class="text-xl font-bold text-white mb-1">Solicitar Cotización Formal</h3>
      <p class="text-sm text-gray-400 mb-6">Un experto de CCA te contactará en menos de 24 horas.</p>

      <div x-show="leadEnviado" x-cloak class="text-center py-6">
        <div class="text-5xl mb-3">✅</div>
        <p class="text-lg font-bold text-gold-400">¡Solicitud enviada!</p>
        <p class="text-sm text-gray-400 mt-2">Nos comunicaremos pronto a tu correo.</p>
      </div>

      <form x-show="!leadEnviado" @submit.prevent="enviarLead()" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre completo *</label>
          <input x-model="lead.nombre" placeholder="Tu nombre" required
            class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1.5">Empresa</label>
          <input x-model="lead.empresa" placeholder="Nombre de tu empresa"
            class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1.5">Email *</label>
          <input type="email" x-model="lead.email" placeholder="correo@empresa.com" required
            class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1.5">Teléfono / WhatsApp</label>
          <input x-model="lead.telefono" placeholder="+57 300 000 0000"
            class="input-field w-full rounded-lg px-4 py-2.5 text-sm">
        </div>

        <div x-show="leadError" class="p-3 rounded-lg bg-red-900/30 border border-red-500/30">
          <p class="text-red-400 text-xs" x-text="leadError"></p>
        </div>

        <button type="submit" :disabled="leadLoading"
          class="btn-gold w-full text-navy-900 font-bold py-3 rounded-xl flex items-center justify-center gap-2 disabled:opacity-60">
          <span x-show="!leadLoading">Enviar Solicitud</span>
          <span x-show="leadLoading" x-cloak class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Enviando...
          </span>
        </button>
      </form>
    </div>
  </div>

</div>

<script>
function cotizador() {
  return {
    loading: false,
    errorMsg: '',
    resultado: null,
    cotizacionId: null,
    modal: false,
    leadEnviado: false,
    leadLoading: false,
    leadError: '',

    form: {
      origen: '',
      origenLibre: '',
      destino: '',
      tipo_carga: '',
      tipo_carga_label: '',
      peso: '',
      volumen: '',
      tipo_mercancia: '',
      valor_comercial: '',
      requiere_seguro: false,
    },

    lead: {
      nombre: '',
      empresa: '',
      email: '',
      telefono: '',
    },

    get origenFinal() {
      return this.form.origen === 'otro' ? this.form.origenLibre : this.form.origen;
    },

    validar() {
      if (!this.origenFinal) return 'Selecciona o escribe el puerto de origen.';
      if (!this.form.destino) return 'Ingresa el puerto de destino.';
      if (!this.form.tipo_carga) return 'Selecciona el tipo de carga.';
      if (!this.form.peso || parseFloat(this.form.peso) <= 0) return 'Ingresa un peso válido.';
      if (this.form.tipo_carga === 'LCL' && (!this.form.volumen || parseFloat(this.form.volumen) <= 0)) {
        return 'Ingresa el volumen en CBM para carga LCL.';
      }
      if (!this.form.tipo_mercancia.trim()) return 'Describe el tipo de mercancía.';
      return null;
    },

    async cotizar() {
      this.errorMsg = '';
      this.resultado = null;

      const error = this.validar();
      if (error) { this.errorMsg = error; return; }

      const labels = { FCL20: "FCL 20'", FCL40: "FCL 40'", FCL40HC: "FCL 40' HC", LCL: 'LCL' };
      this.form.tipo_carga_label = labels[this.form.tipo_carga] || this.form.tipo_carga;

      this.loading = true;
      try {
        const payload = {
          origen: this.origenFinal,
          destino: this.form.destino,
          tipo_carga: this.form.tipo_carga,
          peso: this.form.peso,
          volumen: this.form.tipo_carga === 'LCL' ? this.form.volumen : null,
          tipo_mercancia: this.form.tipo_mercancia,
          valor_comercial: this.form.valor_comercial || null,
          requiere_seguro: this.form.requiere_seguro,
        };

        const res = await fetch('/cotizar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (!res.ok || !data.success) {
          this.errorMsg = data.mensaje || 'Error al procesar la cotización. Inténtalo nuevamente.';
          return;
        }

        this.resultado = data.resultado;
        this.cotizacionId = data.cotizacion_id;
        this.$nextTick(() => {
          document.querySelector('[x-show="resultado"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

      } catch (e) {
        this.errorMsg = 'Error de conexión. Verifica tu internet e inténtalo nuevamente.';
      } finally {
        this.loading = false;
      }
    },

    abrirModal() {
      this.leadEnviado = false;
      this.leadError = '';
      this.modal = true;
    },

    async enviarLead() {
      this.leadError = '';
      if (!this.lead.nombre.trim()) { this.leadError = 'Ingresa tu nombre.'; return; }
      if (!this.lead.email.trim()) { this.leadError = 'Ingresa tu email.'; return; }

      this.leadLoading = true;
      try {
        const res = await fetch('/leads', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({ ...this.lead, cotizacion_id: this.cotizacionId }),
        });
        const data = await res.json();
        if (data.success) {
          this.leadEnviado = true;
          setTimeout(() => { this.modal = false; this.leadEnviado = false; }, 3000);
        } else {
          this.leadError = 'Error al enviar. Inténtalo nuevamente.';
        }
      } catch (e) {
        this.leadError = 'Error de conexión.';
      } finally {
        this.leadLoading = false;
      }
    },
  }
}
</script>
@endsection
