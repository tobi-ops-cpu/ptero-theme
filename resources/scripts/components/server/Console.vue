<template>
  <div id="console-container" class="h-full flex flex-col">
    <!-- Terminal Output -->
    <div ref="terminal" class="flex-1 bg-black text-green-400 font-mono text-sm rounded-lg overflow-hidden"></div>

    <!-- Command Input -->
    <div class="mt-4 flex items-center space-x-2">
      <input
        v-model="inputCommand"
        @keyup.enter="sendCommand"
        type="text"
        placeholder="Type a command..."
        class="flex-1 bg-card border border-gray rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent"
      />
      <button
        @click="sendCommand"
        class="bg-accent text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition-all glow"
      >
        Send
      </button>
    </div>
  </div>
</template>

<script>
import { Terminal } from 'xterm'
import { FitAddon } from 'xterm-addon-fit'
import 'xterm/css/xterm.css'

export default {
  name: 'ServerConsole',
  props: {
    server: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      socket: null,
      term: null,
      fitAddon: new FitAddon(),
      inputCommand: '',
    }
  },
  mounted() {
    this.initTerminal()
    this.connectWebSocket()

    // Resize terminal when window resizes
    window.addEventListener('resize', this.handleResize)
  },
  methods: {
    initTerminal() {
      this.term = new Terminal({
        theme: {
          background: '#000000',
          foreground: '#33ff33',
          cursor: '#ffffff',
        },
        fontSize: 14,
        fontFamily: 'monospace',
        scrollback: 1000,
        disableStdin: false,
        convertEol: true,
      })
      this.term.loadAddon(this.fitAddon)
      this.term.open(this.$refs.terminal)
      this.fitAddon.fit()
    },
    connectWebSocket() {
      try {
        const token =
          document.head.querySelector('meta[name="csrf-token"]')?.content || ''

        const wsUrl = `${
          window.location.protocol === 'https:' ? 'wss' : 'ws'
        }://${window.location.host}/api/servers/${this.server.id}/ws`

        this.socket = new WebSocket(wsUrl, [token])

        this.socket.onopen = () => {
          this.term.writeln('\x1b[32m✔ Connected to server console.\x1b[0m')
        }

        this.socket.onmessage = (event) => {
          try {
            const data = JSON.parse(event.data)
            if (data?.data?.output) {
              this.term.write(data.data.output)
            }
          } catch (err) {
            console.error('Invalid WS message:', err)
          }
        }

        this.socket.onerror = (err) => {
          this.term.writeln('\x1b[31m✖ WebSocket error occurred.\x1b[0m')
          console.error('WebSocket error:', err)
        }

        this.socket.onclose = () => {
          this.term.writeln(
            '\x1b[33m⚠ Disconnected from server. Refresh to reconnect.\x1b[0m'
          )
        }
      } catch (err) {
        console.error('WS init failed:', err)
      }
    },
    sendCommand() {
      if (!this.inputCommand.trim() || !this.socket) return

      this.socket.send(
        JSON.stringify({
          event: 'send command',
          args: [this.inputCommand],
        })
      )

      // Echo the command in console
      this.term.write(`\r\n\x1b[36m$ ${this.inputCommand}\x1b[0m\r\n`)
      this.inputCommand = ''
    },
    handleResize() {
      if (this.fitAddon) this.fitAddon.fit()
    },
  },
  beforeDestroy() {
    if (this.socket) this.socket.close()
    if (this.term) this.term.dispose()
    window.removeEventListener('resize', this.handleResize)
  },
}
</script>
