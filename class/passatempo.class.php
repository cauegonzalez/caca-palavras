<?php
/**
 * Classe responsável por criar o passatempo
 * 14/12/2015
 *
 * @author cauegonzalez@gmail.com
 */
class Passatempo
{
    public $matrizCacaPalavras;
    public $arrayPalavrasAdicionadas;
    public $arrayPalavrasNaoAdicionadas;

    function __construct()
    {
        ;
    }

    /**
     * Método que cria a matriz que será utilizada para o caça-palavras
     * 14/12/2015
     *
     * @param integer $altura
     * @param integer $largura
     * @author cauegonzalez@gmail.com
     * @return array
     */
    private function criaMatrizCacaPalavras($altura, $largura)
    {
        //inicializa variável de retorno
        $this->matrizCacaPalavras = array();

        // inicializa todas as posições da matriz com valor vazio
        for ($i = 0; $i < $largura; $i++)
        {
            for ($j = 0; $j < $altura; $j++)
            {
                $this->matrizCacaPalavras[$i][$j] = "";
            }
        }

        return $this->matrizCacaPalavras;
    }

    /**
     * Método que determina a direção da palvra:  0 = horizontal; 1 = vertical
     * 14/12/2015
     *
     * @param integer $tamanhoPalavra
     * @param integer $altura
     * @param integer $largura
     * @author cauegonzalez@gmail.com
     * @return integer
     */
    private function determinaDirecao($tamanhoPalavra, $altura, $largura)
    {
        if (($tamanhoPalavra > $altura) && ($tamanhoPalavra < $largura))
        {
            $direcao = 0;
        }
        else if (($tamanhoPalavra < $altura) && ($tamanhoPalavra > $largura))
        {
            $direcao = 1;
        }
        else
        {
            $direcao = rand(0, 1);
        }

        return $direcao;
    }

    /**
     * Método responsável por posicionar uma palavra na matriz do caça-palavras
     * 14/12/2015
     *
     * @param integer $altura
     * @param integer $largura
     * @param string $palavra
     * @author cauegonzalez@gmail.com
     * @return boolean
     */
    private function posicionaPalavra($altura, $largura, $palavra)
    {
        // para permitir palavras com caracteres especiais, é necessário removê-los
        $palavra = $this->retiraCaracteresEspeciais($palavra);
        $tamanhoPalavra = strlen($palavra);

        if (($tamanhoPalavra > $altura) && ($tamanhoPalavra > $largura))
        {
            return false;
        }
        // inicializa variável para saber se conseguiu posicionar a palavra ou não
        $conseguiu = false;
        // utiliza um contador para determinar um limite de tentativas para evitar loop infinito
        $limiteTentativas = 0;
        do
        {
            // determina a direção da palavra: 0 = horizontal; 1 = vertical
            $direcao = $this->determinaDirecao($tamanhoPalavra, $altura, $largura);

            // se a direção for horizontal, a posição inicial não deve ser maior do que $largura - $tamanhoPalavra (análogo para a direção vertical)
            if ($direcao == 0)
            {
                $maximoLargura = $largura - $tamanhoPalavra;
                $maximoAltura = $altura - 1;
                $iInicial = rand(0, $maximoLargura);
                $jInicial = rand(0, $maximoAltura);
            }
            else
            {
                $maximoLargura = $largura - 1;
                $maximoAltura = $altura - $tamanhoPalavra;
                $iInicial = rand(0, $maximoLargura);
                $jInicial = rand(0, $maximoAltura);
            }

            // verifica se a posição selecionada está disponível para posicionar a palavra fornecida
            $posicaoDisponivel = $this->verificaDisponibilidade($iInicial, $jInicial, $palavra, $direcao);
            // caso esteja disponível, escreve a palavra na matriz do caça-palavras
            if ($posicaoDisponivel)
            {
                $conseguiu = $this->escrevePalavra($iInicial, $jInicial, $palavra, $direcao);
            }
            else
            {
                // incrementa a variável que limita a quantidade de tentativas
                $limiteTentativas++;
            }
        } while ((!$posicaoDisponivel) || ($limiteTentativas == 100));

        return $conseguiu;
    }

    /**
     * Método que verifica a disponibilidade de iniciar a palavra nas posições $iInicial e $jInicial
     * 14/12/2015
     *
     * @param integer $iInicial
     * @param integer $jInicial
     * @param string $palavra
     * @param integer $direcao
     * @author cauegonzalez@gmail.com
     * @return boolean
     */
    private function verificaDisponibilidade($iInicial, $jInicial, $palavra, $direcao)
    {
        // captura o tamanho da palavra
        $tamanhoPalavra = strlen($palavra);
        // converte a palavra em um array
        $arrayPalavra = str_split($palavra);
        // percorre todas as posições necessárias para escrever a palavra na matriz

        // inicializa a variável para percorrer a palavra
        $posicaoPalavra = 0;

        if ($direcao == 0)
        {
            // se entrou aqui, a direção é horizontal, percorrer horizontalmente
            for ($i = $iInicial; $i < ($iInicial + $tamanhoPalavra); $i++)
            {
                // verificando se a posição está vazia ou preenchida com a letra correspondente da palavra
                if (($this->matrizCacaPalavras[$i][$jInicial] == "") || ($arrayPalavra[$posicaoPalavra] == $this->matrizCacaPalavras[$i][$jInicial]))
                {
                    // caso sim, verificar a próxima
                    $posicaoPalavra++;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            // se entrou aqui, a direção é vertical, percorrer verticalmente
            for ($j = $jInicial; $j < ($jInicial + $tamanhoPalavra); $j++)
            {
                // verificando se a posição está vazia ou preenchida com a letra correspondente da palavra
                if (($this->matrizCacaPalavras[$iInicial][$j] == "") || ($arrayPalavra[$posicaoPalavra] == $this->matrizCacaPalavras[$iInicial][$j]))
                {
                    // caso sim, verificar a próxima
                    $posicaoPalavra++;
                }
                else
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Método responsável por escrever a palavra na matriz do caça-palavras
     * 14/12/2015
     *
     * @param integer $iInicial
     * @param integer $jInicial
     * @param string $palavra
     * @param integer $direcao
     * @author cauegonzalez@gmail.com
     */
    private function escrevePalavra($iInicial, $jInicial, $palavra, $direcao)
    {
        // captura o tamanho da palavra
        $tamanhoPalavra = strlen($palavra);
        // converte a palavra em um array
        $arrayPalavra = str_split($palavra);
        // percorre todas as posições necessárias para escrever a palavra na matriz

        // inicializa a variável para percorrer a palavra
        $posicaoPalavra = 0;

        if ($direcao == 0)
        {
            // se entrou aqui, a direção é horizontal, percorrer horizontalmente
            for ($i = $iInicial; $i < $iInicial + $tamanhoPalavra; $i++)
            {
                // preencher com a letra correspondente da palavra
                $this->matrizCacaPalavras[$i][$jInicial] = $arrayPalavra[$posicaoPalavra];
                $posicaoPalavra++;
            }
        }
        else
        {
            // se entrou aqui, a direção é vertical, percorrer verticalmente
            for ($j = $jInicial; $j < $jInicial + $tamanhoPalavra; $j++)
            {
                // preencher com a letra correspondente da palavra
                $this->matrizCacaPalavras[$iInicial][$j] = $arrayPalavra[$posicaoPalavra];
                $posicaoPalavra++;
            }
        }
        return true;
    }

    /**
     * Método responsável por imprimir a matriz resultante na tela
     * 14/12/2015
     *
     * @param integer $altura
     * @param integer $largura
     * @author cauegonzalez@gmail.com
     */
    private function imprimeMatrizCacaPalavras($altura, $largura)
    {
        $strColuna = "";
        $strLinhas = "";
        $strTabela = "";
        $templateColuna = file_get_contents("templates/colunas.template.html");
        $templateLinhas = file_get_contents("templates/linhas.template.html");
        $templateTabela = file_get_contents("templates/tabela.template.html");
        // percorre todas as posições da matriz e imprime na tela
        for ($i = 0; $i < $largura; $i++)
        {
            for ($j = 0; $j < $altura; $j++)
            {
                // monta a coluna com seu respectivo valor
                $strColuna .= str_replace("{COLUNA}", $this->matrizCacaPalavras[$i][$j], $templateColuna);
            }
            //monta a linha
            $strLinhas .= str_replace("{COLUNAS}", $strColuna, $templateLinhas);
            //reinicializa variável das colunas
            $strColuna = "";
        }

        $strTabela = str_replace("{LINHAS}", $strLinhas, $templateTabela);
        // escreve a tabela HTML que conterá o quadro do caça-palavras
        echo $strTabela;
    }

    private function retiraCaracteresEspeciais($elemento)
    {
        $arrayChaves  = array('á','à','ã','â','ä','é','è','ê','ë','í','ì','î','ï','ó','ò','õ','ô','ö','ú','ù','û','ü','ç','Á','À','Ã','Â','Ä','É','È','Ê','Ë','Í','Ì','Î','Ï','Ó','Ò','Õ','Ö','Ô','Ú','Ù','Û','Ü','Ç',' ', ',');
        $arrayValores = array('A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C','A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C','','');
        if (sizeof($elemento) == 0)
        {
            return false;
        }
        else
        {
            return str_replace($arrayChaves, $arrayValores, $elemento);
        }
    }

    /**
     * Método responsável por criar um novo Caça-palavras
     * 14/12/2015
     *
     * @param integer $largura
     * @param integer $altura
     * @param boolean $completar
     * @param array $arrayPalavras
     * @author cauegonzalez@gmail.com
     */
    public function novoCacaPalavras($largura, $altura, $completar, $arrayPalavras)
    {
        // retira palavras em branco
        $arrayDasPalavras = array_filter($arrayPalavras);
        // converte tudo para letras maiúsculas para ficar mais apresentável
        $arrayDasPalavras = array_map('strtoupper', $arrayDasPalavras);

        // cria a matriz com o tamanho especificado
        $this->criaMatrizCacaPalavras($altura, $largura);

        // tenta inserir cada palavra informada no array
        foreach ($arrayDasPalavras as $palavra)
        {
            // se conseguir posicionar a palavra na matriz, posiciona e insere a mesma no array de palavras Adicionadas
            if ($this->posicionaPalavra($altura, $largura, $palavra))
            {
                $this->arrayPalavrasAdicionadas[] = $palavra;
            }
            else
            {
                // se não conseguir posicionar a palavra na matriz, insere a mesma no array de palavras NÃO Adicionadas
                $this->arrayPalavrasNaoAdicionadas[] = $palavra;
            }
        }

        if ($completar)
        {
            $this->completaMatrizCacaPalavras($largura, $altura);
        }

        $this->imprimeMatrizCacaPalavras($altura, $largura);

        $stringAdicionadas = file_get_contents("templates/palavrasAdicionadas.template.html");
        $words = print_r($this->arrayPalavrasAdicionadas, 1);
        $stringAdicionadas = str_replace("{PALAVRAS_ADICIONADAS}", $words, $stringAdicionadas);
        echo $stringAdicionadas;

        $stringNaoAdicionadas = file_get_contents("templates/palavrasNaoAdicionadas.template.html");
        $words = print_r($this->arrayPalavrasNaoAdicionadas, 1);
        $stringNaoAdicionadas = str_replace("{PALAVRAS_NAO_ADICIONADAS}", $words, $stringNaoAdicionadas);
        echo $stringNaoAdicionadas;
    }

    /**
     * Método responsável por preencher as posições vazias do quadro do Caça-palavras
     * 14/12/2015
     *
     * @param integer $largura
     * @param integer $altura
     * @author cauegonzalez@gmail.com
     */
    private function completaMatrizCacaPalavras($largura, $altura)
    {
        // percorre a matriz para preencher as posições ainda vazias
        for ($i = 0; $i < $largura; $i++)
        {
            // percorre a matriz para preencher as posições ainda vazias
            for ($j = 0; $j < $altura; $j++)
            {
                // se a célula estiver vazia, preencher com uma letra aleatória
                if ($this->matrizCacaPalavras[$i][$j] == "")
                {
                    $arrayLetras = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                    $letraRandomica = $arrayLetras[rand(0, 25)];
                    $this->matrizCacaPalavras[$i][$j] = $letraRandomica;
                }
            }
        }
    }
}
