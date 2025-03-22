@extends('layouts.contentNavbarLayout')

@section('title', 'Page Builder')

@section('content')

  <div class="container-fluid mt-4">
    <h4>Editing Event: {{ $event->Title }}</h4>
    <button id="savePage" class="btn btn-primary">Save Page</button>
    <a href="{{ route('events.view', ['id' => $event->EventID]) }}" class="btn btn-success">View Page</a>

    <div class="d-flex">
    <!-- Blocks Panel (Left Sidebar) -->
    <div id="blocks" style="width: 250px; padding: 10px; background: #f8f9fa; border-right: 1px solid #ddd;">
      <h5>Blocks</h5>
    </div>

    <!-- GrapesJS Editor (Middle) -->
    <div id="gjs" style="height: 800px; flex-grow: 1;"></div>

    <!-- Style Manager Panel (Right Sidebar) -->
    <div id="style-manager" style="width: 250px; padding: 10px; background: #f8f9fa; border-left: 1px solid #ddd;">
      <h5>Styles</h5>
    </div>
    </div>
  </div>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.20.2/css/grapes.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.20.2/grapes.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const editor = grapesjs.init({
      container: '#gjs',
      height: '800px',
      width: 'auto',
      fromElement: true,
      storageManager: false,
      blockManager: {
      appendTo: '#blocks',
      },
      styleManager: {
      appendTo: '#style-manager',
      sectors: [{
        name: 'General',
        open: false,
        buildProps: ['width', 'min-height', 'background-color', 'color', 'font-size', 'text-align'],
      }]
      },
    });

    // Add basic blocks
    const blockManager = editor.BlockManager;
    blockManager.add('text', {
      label: 'Text',
      content: '<p>This is a simple text block</p>',
      category: 'Basic',
    });

    blockManager.add('heading', {
      label: 'Heading',
      content: '<h1>Heading Title</h1>',
      category: 'Basic',
    });

    blockManager.add('image', {
      label: 'Image',
      content: '<img src="https://via.placeholder.com/150" alt="Placeholder Image">',
      category: 'Basic',
    });

    blockManager.add('button', {
      label: 'Button',
      content: '<button class="btn btn-primary">Click Me</button>',
      category: 'Basic',
    });

    blockManager.add('section', {
      label: 'Section',
      content: '<section style="padding: 20px; background: #eee;">New Section</section>',
      category: 'Layout',
    });

    // Load saved content if available
    @if($pageContent)
    editor.setComponents({!! json_encode($pageContent->html) !!});
    editor.setStyle({!! json_encode($pageContent->css) !!});
  @endif

    // Save button functionality
    document.getElementById('savePage').addEventListener('click', function () {
      const html = editor.getHtml();
      const css = editor.getCss();

      fetch("{{ route('events.builder.save', ['id' => $event->EventID]) }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ html, css })
      }).then(response => response.json()).then(data => {
      alert(data.message);
      });
    });
    });
  </script>

@endsection