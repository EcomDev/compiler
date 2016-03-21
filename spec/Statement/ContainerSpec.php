<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContainerSpec extends ObjectBehavior
{
    function it_allows_to_specify_items_via_constructor(StatementInterface $item1, StatementInterface $item2)
    {
        $this->beConstructedWith([$item1, $item2]);
        $this->getIterator()->shouldMatchArray([$item1, $item2]);
    }

    function it_allows_to_add_items_later(StatementInterface $item1, StatementInterface $item2)
    {
        $this->add($item1)->shouldReturn($this);
        $this->add($item2)->shouldReturn($this);

        $this->getIterator()->shouldMatchArray([$item1, $item2]);
    }

    public function getMatchers()
    {
        return [
            'matchArray' => function ($subject, $array) {
                if ($subject instanceof \Traversable) {
                    $subject = iterator_to_array($subject);
                }

                return $subject == $array;
            }
        ];
    }


}
