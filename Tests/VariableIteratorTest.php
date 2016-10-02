<?php

namespace JLaso\Iterators;

use PHPUnit\Framework\TestCase;

class VariableIteratorTest extends TestCase
{
    public function testNumVariations()
    {
        $options = [];
        $numOptions = 1;
        for ($i = 0; $i < rand(1, 10); $i++) {
            $name = "option-{$i}";
            for ($j = 0; $j < rand(1, 10); $j++) {
                $options[$name][] = 1000 * $i + $j;
            }
            $numOptions *= count($options[$name]);
        }

        $iterator = new VariableIterator($options);
        $result = [];
        foreach ($iterator as $item) {
            $result[] = join(',', $item);
        }

        $this->assertEquals(count($result), $numOptions);

        // getting the values as keys, all the elements has to be different
        $this->assertEquals(count(array_unique($result)), $numOptions);
    }

    public function testExistVariation()
    {
        $options = [
            // variations => values
            '12' => [1, 2, 3, 4, 5],    // each one should appear 12 times => 4*3
            '15' => [100, 200, 300, 400],
            '20' => [1000, 2000, 3000],
        ];

        $iterator = new VariableIterator($options);

        $sums = [];
        $sums2 = [];
        // check that all the items are presented
        foreach ($iterator as $item) {
            $sum = array_sum($item);
            $this->assertTrue($sum > 1000);
            $this->assertTrue($sum % 100 != 0);

            $item2 = $item;
            asort($item2);
            $key = join('-', $item2);
            isset($sums[$key]) ? $sums[$key]++ : $sums[$key] = 1;
            foreach ($item as $t => $i) {
                isset($sums2[$t][$i]) ? $sums2[$t][$i]++ : $sums2[$t][$i] = 1;
            }
        }

        // each combination should appear one time only
        $this->assertEquals(1, max($sums));
        $this->assertEquals(1, min($sums));

        // checking that the combinations matches
        foreach ($sums2 as $total => $sums) {
            $this->assertEquals(max($sums), min($sums));
            $this->assertEquals($total, min($sums));
        }
    }

    /**
     * @expectedException     \JLaso\Iterators\Exception\WrongParameterType
     */
    public function testWrongParameterException()
    {
        $options = [
            'a' => [1, 2, 3, 4],
            'b' => [100, 200, 300, 400],
            'c' => [1000, 2000, 3000, [1, 2, 3]],
        ];

        $iterator = new VariableIterator($options);
    }
}
