    <?php

    require_once __DIR__ . '/vendor/autoload.php';

    use GuzzleHttp\Client as Guzz;
    use Predis\Client as Predis;

    $guzz = new Guzz([
        'base_uri' => 'http://servicodados.ibge.gov.br/api/v1/localidades/',
        'timeout' => 60.0,         
    ]);


    $predis = new Predis([
       'scheme' => 'tls',  
       'host'   => 'above-redbird-51221.upstash.io',
       'port'   => 6379,
       'password' => 'AcgVAAIjcDFiMDI3Y2E5MmM5MzQ0Zjc5YmI5N2UxNzUyZTI1Y2Y4OHAxMA', 
    ]);


    $ufs = [
      'AC',
      'AL',
      'AP',
      'AM',
      'BA',
      'CE',
      'DF',
      'ES',
      'GO',
      'MA',
      'MS',
      'MT',
      'MG',
      'PA',
      'PB',
      'PR',
      'PE',
      'PI',
      'RJ',
      'RN',
      'RS',
      'RO',
      'RR',
      'SC',
      'SP',
      'SE',
      'TO'
    ];

    $teste = $predis->get('localidades:quantidade');

    if(empty($teste)){
      $teste = 0;
      foreach ($ufs as $uf){
        $res = $guzz->get("estados/{$uf}/municipios");
        $teste += count(json_decode($res->getBody()));
      }

      $predis->set('localidades:quantidade',$teste );
       $predis->expire('localidades:quantidade', 60);
    }




    ?>

    <html>
      <head>
        <title>PHP Test</title>
      </head>
      <body>

      <h1>Aprendendo Redis</h1>

      <p>Total de municipios no brasil, api ibge: <?php echo $teste; ?></p>

      <p>Essa solicitação sem o Redis leva em media 15 segundos</p>

      <p>Por 60 segundos o Redis esta otimizando esse tempo.</p>
      <p>Agora a solicitação com o Redis leva em media 1 segundo</p>
    </html>
