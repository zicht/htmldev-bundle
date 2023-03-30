<?php declare(strict_types=1);
/**
 * @copyright Zicht Online <https://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuUriVoter implements VoterInterface
{
    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     */
    public function matchItem(ItemInterface $item): ?bool
    {
        return $this->requestStack->getMainRequest()->getPathInfo() === $item->getUri() ? true : null;
    }
}
