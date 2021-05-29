<?php

namespace ycl123\queue;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;

class Queue extends Command
{
    protected function configure(): void
    {
        // 指令配置
        $this->setName('ycl123:queue')
            ->addArgument('action', Argument::OPTIONAL, 'start|stop|restart|reload|status|connections', 'start')
            ->addOption('daemon', 'd', Option::VALUE_NONE, 'Run the scheduler service in daemon mode.')
            ->setDescription('the queue service');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return bool
     */
    protected function execute(Input $input, Output $output): bool
    {
        // 判断指令
        $action = $input->getArgument('action');
        if (DIRECTORY_SEPARATOR !== '\\') {
            if (!in_array($action, ['start', 'stop', 'reload', 'restart', 'status', 'connections'])) {
                $output->writeln(
                    "<error>Invalid argument action:{$action}, Expected start|stop|restart|reload|status|connections .</error>"
                );
                return false;
            }

            global $argv;
            array_shift($argv);
            array_shift($argv);
            array_unshift($argv, 'think', $action);
        } elseif ('start' !== $action) {
            $output->writeln("<error>Not Support action:{$action} on Windows.</error>");
            return false;
        }

        // 获取配置
        $config = Config::get('ycl123_queue');
        if ($input->hasOption('daemon')) {
            $config['daemonize'] = true;
        }

        // 启动
        $start = new Start($config);
        $start->run();
        return true;
    }
}
